<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\AccountOpeningDocument;
use App\Models\DtrfDocument;
use App\Models\GoldLoanDocument;
use App\Models\LoanDocument;
use App\Models\CourierDispatch;
use App\Models\Courier;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\VendorDocumentImport;
use Auth;
use DB;
use App\Models\ProcessStatus;
use App\Models\DocumentHistory;
use Illuminate\Support\Str;
use App\Models\Vendor;
use App\Models\User;
use App\Exports\LoanDocumentExport;
use App\Exports\GoldLoanDocumentExport;
use App\Exports\DtrfExport;
use App\Exports\AccountOpeningDocumentExport;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class DocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            $this->table = [
                'loan' => LoanDocument::class,
                'goldloan' => GoldLoanDocument::class,
                'dtrf' => DtrfDocument::class,
                'aof' => AccountOpeningDocument::class,
            ];
            return $next($request);
        });
    }
    public function index($type, $dtype)
    {
        $start_date = Carbon::now()->subWeek()->startOfWeek(); 
        $end_date = Carbon::now()->subWeek()->endOfWeek();

        $filter = function ($query) use ($type) {
            if($type === 'moved'){
                $query->where('status','>=',8);
            }
            // elseif($type === 'received'){
            //     if ($this->user->hasRole('bo-maker') || $this->user->hasRole('bo-checker')) {
            //         $query->where('status','>=',8);
            //     }
            //     $query->whereIn('status',[5,7]);
            // }

            elseif($type === 'received') {
                if ($this->user->hasRole('bo-maker') || $this->user->hasRole('bo-checker') || $this->user->hasRole('branch-user')) {
                    $query->whereIn('status', [5, 7, 8, 9, 10, 11]);
                } else {
                    $query->whereIn('status', [5, 7]);
                }
            }            
            elseif($type ==='rejected'){
                $query->where('status',6);
            }
            elseif($type ==='pending'){
                $query->where('status',1);
            }
            if ($this->user->hasRole('ro-officer') || $this->user->hasRole('ro-supervisor') || $this->user->hasRole('ro-user')) {
                $query->where('region', $this->user->region);
            }
            if ($this->user->hasRole('bo-maker') || $this->user->hasRole('bo-checker') || $this->user->hasRole('branch-user')) {
                $query->where('branch_code', $this->user->branch_id);
            }
            return $query->orderBy('account_creation_date', 'desc');
        };
        $loan_document = $filter(LoanDocument::query())->paginate(100)->withQueryString()->withPath(url("/documents/{$type}/loan"));
        $gold_loan_document = $filter(GoldLoanDocument::query())->paginate(100)->withQueryString()->withPath(url("/documents/{$type}/goldloan"));
        $dtrf_document = $filter(DtrfDocument::query())->paginate(100)->withQueryString()->withPath(url("/documents/{$type}/dtrf"));
        $account_opening_document = $filter(AccountOpeningDocument::query())->paginate(100)->withQueryString()->withPath(url("/documents/{$type}/aof"));
    
        $loan_total = $loan_document->total();
        $gold_loan_total = $gold_loan_document->total();
        $dtrf_total = $dtrf_document->total();
        $aof_total = $account_opening_document->total();
        $process_statuses = ProcessStatus::where('status', 1)->get();
        $vendors = Vendor::all();

        $fixedStatuses = [
            'pending' => 1,
            'rejected' => 6,
            'received' => [5, 7],
        ];

        $fixed_status = $fixedStatuses[$type] ?? null;
        
        if ($fixed_status) {
            $filters['status'] = $fixed_status;
        }

        if($type != 'moved')
            return view('accounts.accounts', compact('loan_document', 'gold_loan_document', 'dtrf_document', 'account_opening_document', 'type', 'dtype', 'loan_total', 'gold_loan_total', 'dtrf_total', 'aof_total', 'process_statuses', 'vendors', 'fixed_status'));
        else
            return view('accounts.vendor_view', compact('loan_document', 'gold_loan_document', 'dtrf_document', 'account_opening_document', 'type', 'dtype', 'loan_total', 'gold_loan_total', 'dtrf_total', 'aof_total', 'vendors'));
    }
    public function filter(Request $request)
    {
        $parsedUrl = parse_url(url()->previous());       
        $url = explode('/', trim($parsedUrl['path'], '/'));
        session(['type' => isset($url[1]) ? $url[1]:null]);
        session(['dtype' => isset($url[2]) ? $url[2]:null]);
        session(['filters' => $request->all()]);

        if ($url[1] == 'proceed')
            return redirect()->route('accounts.selected');
        else
            return redirect()->route('document.filtered');
    }
    
    public function filteredList(Request $request)
    {
        $filters = session()->pull('filters', []);
        
        $type = isset($filters['type']) ? $filters['type'] : session()->pull('type', 'all');
        $dtype = isset($filters['dtype']) ? $filters['dtype'] : session()->pull('dtype', 'loan');
        // $type = $filters['type'] ?? session()->pull('type', 'all');
        // $dtype = $filters['dtype'] ?? session()->pull('dtype', 'loan');
        if(empty($filters)){
            if(isset($type) && isset($dtype))
                return redirect()->route('accounts.index',['type' => $type,'dtype' => $dtype]);
            else
                return redirect()->route('accounts.index',['type' => 'all','dtype' => 'loan']);
        }
        $user = $this->user;
        $hasFilters = collect($filters)->filter()->isNotEmpty();
        $fromDate = $filters['from_date'] ?? null;
        $toDate = $filters['to_date'] ?? null;

        // $fromDate = !empty($filters['from_date']) ? Carbon::createFromFormat('d-m-Y', $filters['from_date'])->format('Y-m-d') : null;
        // $toDate = !empty($filters['to_date']) ? Carbon::createFromFormat('d-m-Y', $filters['to_date'])->format('Y-m-d') : null;
        // dd($toDate);

        $cifId = $filters['cif_id'] ?? null;
        $accountNumber = $filters['account_number'] ?? null;


        // unset($filters['from_date'], $filters['to_date']);

        $docType = $filters['document_type'] ?? null;
        
        $filterFunction = function ($query, $table) use ($user, $filters, $hasFilters, $fromDate, $toDate, $docType, $type) {

            if ($user->hasRole('ro-officer') || $user->hasRole('ro-supervisor')) {
                $query->where('region', $user->region);
            }
        
            if ($user->hasRole('bo-maker') || $user->hasRole('bo-checker') || $user->hasRole('branch-user')) {
                $query->where('branch_code', $user->branch_id);
            }
        
            if ($fromDate !== null && $toDate !== null) {
                $start = Carbon::parse($fromDate)->startOfDay();
                $end   = Carbon::parse($toDate)->endOfDay();
                $query->whereBetween('account_creation_date', [$start, $end]);
            } elseif ($fromDate !== null) {
                $start = Carbon::parse($fromDate)->startOfDay();
                $query->where('account_creation_date', '>=', $start);
            } elseif ($toDate !== null) {
                $end = Carbon::parse($toDate)->endOfDay();
                $query->where('account_creation_date', '<=', $end);
            }
        
            // Only apply this when no specific status is provided
            // if (isset($type) && $type == 'moved' && empty($filters['status'])) {
            //     $query->whereIn('status', [8, 9, 10, 11]);
            // }
            $hasBoRole = $user->hasRole('bo-maker') || $user->hasRole('bo-checker') || $user->hasRole('branch-user');

            if (!empty($filters['status'])) {
                $status = (int) $filters['status'];
        
                if ($hasBoRole && $status == 5) {
                    $query->whereIn('status', [5,8,9,10,11]);
                } elseif (in_array($status, [8,9,10,11])) {
                    $query->where('status', $status);
                } else {
                    $query->where('status', $status);
                }
            } elseif (isset($type) && $type == 'moved') {
                $query->whereIn('status', [8,9,10,11]);
            }
            // if (isset($type) && $type === 'moved') {
            //     if (!empty($filters['status'])) {
            //         // Apply the user-selected status only
            //         $query->where('status', $filters['status']);
            //     } else {
            //         // Apply default moved statuses
            //         $query->whereIn('status', [8, 9, 10, 11]);
            //     }
            // }

            if ($hasFilters) {
                foreach ($filters as $field => $value) {
                    if ($field == 'status') continue;
                    if (!empty($value) && \Schema::hasColumn($table, $field)) {
                        if (in_array($field, ['cif_id', 'account_number','channel'])) {
                            $query->where($field, 'like', '%' . $value . '%');
                        } else {
                            $query->where($field, $value);
                        }
                    }
                }
            }
            return $query->orderBy('account_creation_date', 'desc');
        };
        

        $loan_document = null;
        $gold_loan_document = null;
        $dtrf_document = null;
        $account_opening_document = null;

        if ($docType === 'loan') {
            $loan_document = LoanDocument::where(function ($q) use ($filterFunction) {
                $filterFunction($q, 'loan_documents');
            })->paginate(100)->withQueryString();

        } elseif ($docType === 'goldloan') {
            $gold_loan_document = GoldLoanDocument::where(function ($q) use ($filterFunction) {
                $filterFunction($q, 'gold_loan_documents');
            })->paginate(100)->withQueryString();

        } elseif ($docType === 'dtrf') {
            $dtrf_document = DtrfDocument::where(function ($q) use ($filterFunction) {
                $filterFunction($q, 'dtrf_documents');
            })->paginate(100)->withQueryString();

        } elseif ($docType === 'aof') {
            $account_opening_document = AccountOpeningDocument::where(function ($q) use ($filterFunction) {
                $filterFunction($q, 'account_opening_documents');
            })->paginate(100)->withQueryString();

        } else {
            $loan_document = LoanDocument::where(function ($q) use ($filterFunction) {
                $filterFunction($q, 'loan_documents');
            })->paginate(100)->withQueryString();
            
            $gold_loan_document = GoldLoanDocument::where(function ($q) use ($filterFunction) {
                $filterFunction($q, 'gold_loan_documents');
            })->paginate(100)->withQueryString();
            if (!isset($filters['cif_id']) && !isset($filters['account_number']) && !isset($filters['channel'])) {
                $dtrf_document = DtrfDocument::where(function ($q) use ($filterFunction) {
                    $filterFunction($q, 'dtrf_documents');
                })->paginate(100)->withQueryString();
            }
            $account_opening_document = AccountOpeningDocument::where(function ($q) use ($filterFunction) {
                $filterFunction($q, 'account_opening_documents');
            })->paginate(100)->withQueryString();
        }
        $loan_total =$loan_document != null ? $loan_document->total():0;
        $gold_loan_total = $gold_loan_document != null ? $gold_loan_document->total():0;
        $dtrf_total = $dtrf_document != null ? $dtrf_document->total():0;
        $aof_total = $account_opening_document != null ? $account_opening_document->total():0;
        $process_statuses = ProcessStatus::where('status', 1)->get();
        
        $fixedStatuses = [
            'pending' => 1,
            'rejected' => 6,
            'received' => [5, 7],
            
        ];
        $dtype = $docType != null ? $docType : $dtype;
        $fixed_status = $fixedStatuses[$type] ?? null;
        
        // if ($fixed_status) {
        if (!empty($fixed_status) && !is_array($fixed_status)) {
            $filters['status'] = $fixed_status;
        }
        $vendors = Vendor::all();
        // $dtype = $filters['document_type'] ?? 

        if($type != 'moved')
            return view('accounts.accounts', compact('loan_document', 'gold_loan_document', 'dtrf_document', 'account_opening_document', 'type', 'dtype', 'loan_total', 'gold_loan_total', 'dtrf_total', 'aof_total','filters', 'process_statuses', 'vendors', 'fixed_status' ));
        else
            return view('accounts.vendor_view', compact('loan_document', 'gold_loan_document', 'dtrf_document', 'account_opening_document', 'type', 'dtype', 'loan_total', 'gold_loan_total', 'dtrf_total', 'aof_total','filters', 'process_statuses', 'vendors' ));
    }
    
    public function bulkReview(Request $request)
    {
        if(isset($request->loan_ids)){
            LoanDocument::whereIn('id',$this->decryptIds($request->loan_ids))->get()->each(function ($doc) {
                $doc->status = 2;
                $doc->updated_by = $this->user->id;
                $doc->save();
            });
        }
        if(isset($request->goldloan_ids)){
            GoldLoanDocument::whereIn('id',$this->decryptIds($request->goldloan_ids))->get()->each(function ($doc) {
                $doc->status = 2;
                $doc->updated_by = $this->user->id;
                $doc->save();
            });
        }
        if(isset($request->dtrf_ids)){
            DtrfDocument::whereIn('id',$this->decryptIds($request->dtrf_ids))->get()->each(function ($doc) {
                $doc->status = 2;
                $doc->updated_by = $this->user->id;
                $doc->save();
            });
        }
        if(isset($request->aof_ids)){
            AccountOpeningDocument::whereIn('id',$this->decryptIds($request->aof_ids))->get()->each(function ($doc) {
                $doc->status = 2;
                $doc->updated_by = $this->user->id;
                $doc->save();
            });
        }
        return redirect()->route('accounts.selected');
    }
    
    public function getBulkReview(Request $request)
    {
        $filters = session('filters', []);
        $user = $this->user;
        $hasFilters = collect($filters)->filter()->isNotEmpty();
        $fromDate = $filters['from_date'] ?? null;
        $toDate = $filters['to_date'] ?? null;
    
        // $fromDate = !empty($filters['from_date']) ? Carbon::createFromFormat('d-m-Y', $filters['from_date'])->format('Y-m-d') : null;
        // $toDate = !empty($filters['to_date']) ? Carbon::createFromFormat('d-m-Y', $filters['to_date'])->format('Y-m-d') : null;
    
        // unset($filters['from_date'], $filters['to_date']);
    
        $allDocuments = collect();
    
        $customFilter = function ($query, $table) use ($user, $filters, $hasFilters, $fromDate, $toDate) {
            if ($user->hasRole('ro-officer') || $user->hasRole('ro-supervisor')) {
                $query->where('region', $user->region);
            }
    
            if ($user->hasRole('bo-maker') || $user->hasRole('bo-checker') || $user->hasRole('branch-user')) {
                $query->where('branch_code', $user->branch_id);
            }
    
            if ($fromDate !== null && $toDate !== null) {
                $start = Carbon::parse($fromDate)->startOfDay();
                $end   = Carbon::parse($toDate)->endOfDay();
                $query->whereBetween('account_creation_date', [$start, $end]);
            }
            elseif ($fromDate !== null) {
                $start = Carbon::parse($fromDate)->startOfDay();
                $query->where('account_creation_date', '>=', $start);
            }
            elseif ($toDate !== null) {
                $end = Carbon::parse($toDate)->endOfDay();
                $query->where('account_creation_date', '<=', $end);
            }
    
            if ($hasFilters) {
                foreach ($filters as $field => $value) {
                    if (!empty($value) && \Schema::hasColumn($table, $field)) {
                        if (in_array($field, ['cif_id', 'account_number'])) {
                            $query->where($field, 'like', '%' . $value . '%');
                        } else {
                            $query->where($field, $value);
                        }
                    }
                }
            }
        };
    
        // Additional common status filter
        $statusFilter = function ($query) use ($user) {
            if ($user->hasRole('bo-maker') || $user->hasRole('bo-checker') || $user->hasRole('branch-user')) {
                $query->where('branch_code', $user->branch_id);
            }
            return $query->where('status', 2)->orderBy('updated_at', 'desc');
        };

        $docType = $filters['document_type'] ?? null;
        if (!$docType || $docType == 'loan') {
            $loanQuery = LoanDocument::query();
            $customFilter($loanQuery, 'loan_documents');
            $loans = $statusFilter($loanQuery)->get()->map(function ($item) {
                $item->doc_type = 'loan';
                return $item;
            });
            $allDocuments = $allDocuments->merge($loans);
        }
        
        if (!$docType || $docType == 'goldloan') {
            $goldQuery = GoldLoanDocument::query();
            $customFilter($goldQuery, 'gold_loan_documents');
            $goldloans = $statusFilter($goldQuery)->get()->map(function ($item) {
                $item->doc_type = 'goldloan';
                return $item;
            });
            $allDocuments = $allDocuments->merge($goldloans);
        }
        
        if (!$docType || $docType == 'aof') {
            $aofQuery = AccountOpeningDocument::query();
            $customFilter($aofQuery, 'account_opening_documents');
            $aofs = $statusFilter($aofQuery)->get()->map(function ($item) {
                $item->doc_type = 'aof';
                return $item;
            });
            $allDocuments = $allDocuments->merge($aofs);
        }
        
        if (!isset($filters['cif_id']) && !isset($filters['account_number']) && !isset($filters['channel'])) {
            if (!$docType || $docType == 'dtrf') {
                $dtrfQuery = DtrfDocument::query();
                $customFilter($dtrfQuery, 'dtrf_documents');
                $dtrfs = $statusFilter($dtrfQuery)->get()
                    ->map(function ($item) {
                        $item->doc_type = 'dtrf';
                        return $item;
                    });
                $allDocuments = $allDocuments->merge($dtrfs);
            }
        }

        $process_statuses = ProcessStatus::where('status', 1)->get();
        $couriers = Courier::pluck('name', 'id');
        $type = 'proceed';
    
         return view('accounts.index', compact('allDocuments', 'couriers', 'process_statuses', 'filters', 'type'));
    }
    
    public function addCourierDetails(Request $request)
    {
        $validated = $request->validate([
            // 'dispatch_date' => 'required|date',
            'loan_ids'=> 'nullable|array',
            'goldloan_ids'=> 'nullable|array',
            'dtrf_ids'=> 'nullable|array',
            'aof_ids'=> 'nullable|array'
        ]);
        DB::beginTransaction();
        try {
            // if (isset($request->dispatch_id) && !empty($request->dispatch_id)) {
            //     $dispatch = CourierDispatch::find($validated['dispatch_id']);
            //     $dispatch->courier_id = $validated['courier_name'];
            //     $dispatch->courier_name = $validated['courier_name'];
            //     $dispatch->awb_pod = $validated['awb_pod'];
            //     $dispatch->mmrp_barcode = $validated['mmrp_barcode'];
            //     $dispatch->updated_by = $this->user->id;
            //     $dispatch->save();
            // }else{
                $dispatch = new CourierDispatch;
                $dispatch->branch_code = $this->user->branch_id; 
                $dispatch->region_id = $this->user->region_id; 
                $dispatch->loan_ids= isset($validated['loan_ids']) ? implode(',', $this->decryptIds($validated['loan_ids'])):null;
                $dispatch->goldloan_ids= isset($validated['goldloan_ids']) ? implode(',', $this->decryptIds($validated['goldloan_ids'])):null;
                $dispatch->dtrf_ids= isset($validated['dtrf_ids']) ? implode(',', $this->decryptIds($validated['dtrf_ids'])):null;
                $dispatch->aof_ids= isset($validated['aof_ids']) ? implode(',', $this->decryptIds($validated['aof_ids'])):null;
                $dispatch->status = 3;
                $dispatch->created_by = $this->user->id;
                $dispatch->save();
                $dispatch_id = $dispatch->id;
    
    
                if(isset($request->loan_ids)){
                    LoanDocument::whereIn('id',$this->decryptIds($request->loan_ids))->get()->each(function ($doc) use($dispatch_id) {
                        $doc->status = 3;
                        $doc->dispatch_id = $dispatch_id;
                        $doc->updated_by = $this->user->id;
                        $doc->save();
                    });
                }
                if(isset($request->goldloan_ids)){
                    GoldLoanDocument::whereIn('id',$this->decryptIds($request->goldloan_ids))->get()->each(function ($doc) use($dispatch_id) {
                        $doc->status = 3;
                        $doc->dispatch_id = $dispatch_id;
                        $doc->updated_by = $this->user->id;
                        $doc->save();
                    });
                }
                if(isset($request->dtrf_ids)){
                    DtrfDocument::whereIn('id',$this->decryptIds($request->dtrf_ids))->get()->each(function ($doc) use($dispatch_id) {
                        $doc->status = 3;
                        $doc->dispatch_id = $dispatch_id;
                        $doc->updated_by = $this->user->id;
                        $doc->save();
                    });
                }
                if(isset($request->aof_ids)){
                    AccountOpeningDocument::whereIn('id',$this->decryptIds($request->aof_ids))->get()->each(function ($doc) use($dispatch_id) {
                        $doc->status = 3;
                        $doc->dispatch_id = $dispatch_id;
                        $doc->updated_by = $this->user->id;
                        $doc->save();
                    });
                }
            // }
            DB::commit();
            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
        
    }

    public function getDocumentDetails($type, $id)
    {
        switch ($type) {
            case 'loan':
                $doc = \App\Models\LoanDocument::find($id);
                break;
            case 'goldloan':
                $doc = \App\Models\GoldLoanDocument::find($id);
                break;
            case 'aof':
                $doc = \App\Models\AccountOpeningDocument::find($id);
                break;
            case 'dtrf':
                $doc = \App\Models\DtrfDocument::find($id);
                break;
            default:
                return response()->json(['error' => 'Invalid document type'], 400);
        }
    
        return response()->json($doc);
    }
    
    public function filterDispatches(Request $request, $type)
    {
        session(['filters' => $request->except('_token')]); // Save all filters in session
        return redirect()->route('dispatches', $type);       // Redirect back to listing
    }
    
    public function checkDispatchStatus($id)
    {
        $dispatch = CourierDispatch::find($id);
    
        $hasStatus4 = false;
    
        if ($dispatch) {

            if (!empty($dispatch->loan_ids)) {
                $hasStatus4 = $hasStatus4 || LoanDocument::whereIn('id', explode(',', $dispatch->loan_ids))->where('status', 4)->exists();
            }
            if (!empty($dispatch->goldloan_ids)) {
                $hasStatus4 = $hasStatus4 || GoldLoanDocument::whereIn('id', explode(',', $dispatch->goldloan_ids))->where('status', 4)->exists();
            }
            if (!empty($dispatch->aof_ids)) {
                $hasStatus4 = $hasStatus4 || AccountOpeningDocument::whereIn('id', explode(',', $dispatch->aof_ids))->where('status', 4)->exists();
            }
            if (!empty($dispatch->dtrf_ids)) {
                $hasStatus4 = $hasStatus4 || DtrfDocument::whereIn('id', explode(',', $dispatch->dtrf_ids))->where('status', 4)->exists();
            }
        }
    
        return response()->json(['disable_update' => $hasStatus4]);
    }
    
    
    public function getDispatches($type, Request $request)
    {
        
        // $filters = session('filters', []);
        $filters = session()->pull('filters', []);
        $dispatchDate = $filters['dispatch_date'] ?? null;


        // $dispatchDate = !empty($filters['dispatch_date']) ? Carbon::createFromFormat('d-m-Y', $filters['dispatch_date'])->format('Y-m-d') : null;


        $filter = function ($query) use ($type, $filters, $dispatchDate) {
            if ($this->user->hasRole('ro-officer') || $this->user->hasRole('ro-supervisor') || $this->user->hasRole('ro-user')) {
                $query->where('region_id', $this->user->region_id);
            }

            if ($this->user->hasRole('bo-maker') || $this->user->hasRole('bo-checker') || $this->user->hasRole('branch-user')) {
                $query->where('branch_code', $this->user->branch_id);
            }
            if (!empty($filters['courier'])) {
                $query->where('courier_id', $filters['courier']);
            }
            
            // if ($dispatchDate != null) {
            //     $query->whereDate('dispatch_date', $dispatchDate);
            // }   
            if ($dispatchDate) {
                try {
                    $formattedDate = Carbon::createFromFormat('d-m-Y', $dispatchDate)->format('Y-m-d');
                    $query->whereDate('dispatch_date', $formattedDate);
                } catch (\Exception $e) {
                    // Handle incorrect format or empty input gracefully
                }
            }                    

            if (!empty($filters['dispatch_no'])) {
                $query->where('dispatch_no', 'like', '%' . $filters['dispatch_no'] . '%');
            }
            if (!empty($filters['awb_pod'])) {
                $query->where('awb_pod', 'like', '%' . $filters['awb_pod'] . '%');
            }

            if (!empty($filters['mmrp_barcode'])) {
                $query->where('mmrp_barcode', 'like', '%' . $filters['mmrp_barcode'] . '%');
            }

            if (!empty($filters['branch_code'])) {
                $query->where('branch_code', 'like', '%' . $filters['branch_code'] . '%');
            }

            if (!empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }

            return $query
                ->when($type === 'ready', fn($q) => $q->where('status', 3))
                ->when($type === 'list', fn($q) => $q->where('status', 4))
                ->when($type === 'tracking', fn($q) => $q->whereIn('status', [5, 7]))
                ->when($type === 'delivered', fn($q) => $q->whereIn('status', [12]))
                ->when($type === 'reject', fn($q) => $q->where('status', 6))
                ->orderBy('updated_at', 'desc');
        };

        // Records and counts
        $records = $filter(CourierDispatch::query())->paginate(100);
        // dd($records);
        // Status-wise counts (not affected by form filters)
        
        $ready_to_dispatch_count =  $filter(CourierDispatch::query())->where('status', 3)->count();
        $dispatched_count = $filter(CourierDispatch::query())->where('status', 4)->count();
        $tracking_count = $filter(CourierDispatch::query())->where('status', [5, 7])->count();
        $delivered_count = $filter(CourierDispatch::query())->where('status', 12)->count();
        $reject_count = $filter(CourierDispatch::query())->where('status', 6)->count();


        $process_statuses = ProcessStatus::where('status', 1)->get();
        $couriers = Courier::where('status', 1)->get();
        // $couriers = Courier::pluck('name', 'id');

        return view('accounts.dispatches', compact(
            'records',
            'ready_to_dispatch_count',
            'dispatched_count',
            'tracking_count',
            'delivered_count',
            'reject_count',
            'type',
            'process_statuses',
            'filters',
            'couriers'
        ));
    }
    public function clearFilters($type)
    {
        // $previousUrl = url()->previous(); 
        $previousUrl = url()->previous(); 
        $previousPath = parse_url($previousUrl, PHP_URL_PATH); 
        $previousSegments = explode('/', ltrim($previousPath, '/'));
        $type = Str::afterLast($previousUrl, '/'); 
        // dd($type);
        session()->forget('filters');
        if($type == 'proceed')
            return redirect()->route('accounts.selected');
        elseif($type == 'filter')
            return redirect()->route('document.filtered');
        else
            return redirect()->route('dispatches', $type);
        
    }

    public function viewDispatches($type,$id)
    {
        try {
            $decryptedId = Crypt::decryptString($id);
        } catch (DecryptException $e) {
            abort(404, 'Invalid ID');
        }
        $dispatch = CourierDispatch::findOrFail($decryptedId);

        if ($this->user->hasRole('bo-maker') || $this->user->hasRole('bo-checker') || $this->user->hasRole('branch-user')) {
            if($this->user->branch_id != $dispatch->branch_code){
                return redirect('/home')->with('error', 'Access Denied');
            }
        } elseif ($this->user->hasRole('ro-officer') || $this->user->hasRole('ro-supervisor') || $this->user->hasRole('ro-user')) {
            if($this->user->region_id != $dispatch->region_id){
                return redirect('/home')->with('error', 'Access Denied');
            }
        }
        // $dispatch = CourierDispatch::find($id);
        // // $type = 'dispatch';
        // $previousUrl = url()->previous(); 
        // $type = Str::afterLast($previousUrl, '/');
        $dtype = session()->pull('dtype');
        // dd($dtype);
        $loan_document = LoanDocument::whereIn('id', explode(',', $dispatch->loan_ids))->paginate(1)->withQueryString();
        $gold_loan_document = GoldLoanDocument::whereIn('id', explode(',', $dispatch->goldloan_ids))->paginate(1)->withQueryString();
        $dtrf_document = DtrfDocument::whereIn('id', explode(',', $dispatch->dtrf_ids))->paginate(1)->withQueryString();
        $account_opening_document = AccountOpeningDocument::whereIn('id', explode(',', $dispatch->aof_ids))->paginate(1)->withQueryString();
        $loan_total = $loan_document->total();
        $gold_loan_total = $gold_loan_document->total();
        $dtrf_total = $dtrf_document->total();
        $aof_total = $account_opening_document->total();

        // if($this->user->branch_id != $dispatch->branch_code){
        //     return redirect('/home')->with('error', 'Access Denied');
        // }

        return view('accounts.dispatches_view', compact('dispatch', 'loan_document', 'gold_loan_document', 'dtrf_document', 'account_opening_document', 'type', 'loan_total', 'gold_loan_total', 'dtrf_total', 'aof_total','dtype'));
    }
    

    public function updateCourier(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'courier_name' => 'required|string',
            'awb_pod' => 'nullable|string',
            'mmrp_barcode' => 'required|string',
            'dispatch_id' => 'required'
        ]);
        try {
            $decryptedId = Crypt::decryptString($validated['dispatch_id']);
        } catch (DecryptException $e) {
            abort(404, 'Invalid ID');
        }
        DB::beginTransaction(); // Start Transaction
        try {
            $sequence = CourierDispatch::whereNotNull('dispatch_no')->where('dispatch_date', now()->format('Y-m-d'))->count();
            // dd($sequence);
            // $sequence = CourierDispatch::where('branch_code',$this->user->branch_id)->whereNotNull('dispatch_no')->
            // ->whereDate('created_at', now()->format('Y-m-d'))->first();
            // dd($sequence);
            $dispatched = CourierDispatch::where('id',$decryptedId)->get();
            // $dispatched = CourierDispatch::find('id',$validated['readytodispatch_ids'])->get();
            $dispatchNumbers = [];
            foreach ($dispatched as $dispatch) {
                $sequence++;
                $dispatch->courier_id = $validated['courier_name'];
                $dispatch->courier_name = $validated['courier_name'];
                $dispatch->awb_pod = $validated['awb_pod'];
                $dispatch->mmrp_barcode = $validated['mmrp_barcode'];
                $dispatch->verified_by = Auth::user()->id;
                $dispatch->dispatch_date = date('Y-m-d');
                $dispatch->status = 4;
                // dd($this->buildDispatchNumber($this->user->branch_id, $sequence,now()));
                // $dispatch->dispatch_no = $this->buildDispatchNumber($this->user->branch_id, $sequence,now());
                if (empty($dispatch->dispatch_no)) {
                    $dispatch->dispatch_no = $this->buildDispatchNumber($this->user->branch_id, $sequence, now());
                }                
                $dispatch->save();
                if($dispatch->loan_ids != null){
                    LoanDocument::whereIn('id',explode(',', $dispatch->loan_ids))->get()->each(function ($doc) {
                        $doc->status = 4;
                        $doc->updated_by = $this->user->id;
                        $doc->save();
                    });
                }
                if($dispatch->goldloan_ids != null){
                    GoldLoanDocument::whereIn('id',explode(',', $dispatch->goldloan_ids))->get()->each(function ($doc) {
                        $doc->status = 4;
                        $doc->updated_by = $this->user->id;
                        $doc->save();
                    });
                }
                if($dispatch->dtrf_ids != null){
                    DtrfDocument::whereIn('id',explode(',', $dispatch->dtrf_ids))->get()->each(function ($doc) {
                        $doc->status = 4;
                        $doc->updated_by = $this->user->id;
                        $doc->save();
                    });
                }
                if($dispatch->aof_ids != null){
                    AccountOpeningDocument::whereIn('id',explode(',', $dispatch->aof_ids))->get()->each(function ($doc) {
                        $doc->status = 4;
                        $doc->updated_by = $this->user->id;
                        $doc->save();
                    });
                }
                $dispatchNumbers[] = '#'.$dispatch->dispatch_no;
                // dd($dispatch);
            }
            DB::commit();
            return redirect()->route('dispatches', 'list')->with('success', implode(', ', $dispatchNumbers) . ' Couriers Dispatched Successfully.');
            // return redirect()->route('dispatches','list')->with('success', '<b>' . implode(', ', $dispatchNumbers) . '</b><br>Couriers Dispatched Successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
            // return response()->json([
            //     'success' => true,
            //     'message' => 'Courier created successfully.'
            // ], 200);
            
        }

    }

    public function buildDispatchNumber($branchCode, $sequence, $date)
    {
        $day = $date->format('d');
        $month = $date->format('m');
        $year = $date->format('y');
        $seqStr = str_pad($sequence, 3, '0', STR_PAD_LEFT);

        return "{$branchCode}{$day}{$month}{$year}{$seqStr}";
    }

    public function dispatchDetails(Request $request)
    {
        try {
            DB::beginTransaction();

            $updates = $request->input('updates', []);

            foreach ($updates as $update) {
                $dispatch = CourierDispatch::find($update['id']);
                // $dispatched = CourierDispatch::whereIn('id',$validated['readytodispatch_ids'])->get();


                if (!$dispatch) continue;

                $dispatch->status = $update['remarks'];
                $dispatch->comments = $update['reason_for_rejection'];
                $dispatch->updated_by = $this->user->id;
                $dispatch->save();

                if ((int)$update['remarks'] === 6) {
                    $reason = $update['reason_for_rejection'] ?? null;
                    if (!empty($dispatch->loan_ids)) {
                        LoanDocument::whereIn('id', explode(',', $dispatch->loan_ids))->get()->each(function ($doc) use ($reason) {
                            $doc->status = 6;
                            $doc->reason = $reason;
                            $doc->updated_by = $this->user->id;
                            $doc->save();
                        });
                    }
                    if (!empty($dispatch->goldloan_ids)) {
                        GoldLoanDocument::whereIn('id', explode(',', $dispatch->goldloan_ids))->get()->each(function ($doc) use ($reason) {
                            $doc->status = 6;
                            $doc->reason = $reason;
                            $doc->updated_by = $this->user->id;
                            $doc->save();
                        });
                    }
                    if (!empty($dispatch->aof_ids)) {
                        AccountOpeningDocument::whereIn('id', explode(',', $dispatch->aof_ids))->get()->each(function ($doc) use ($reason) {
                            $doc->status = 6;
                            $doc->reason = $reason;
                            $doc->updated_by = $this->user->id;
                            $doc->save();
                        });
                    }
                    if (!empty($dispatch->dtrf_ids)) {
                        DtrfDocument::whereIn('id', explode(',', $dispatch->dtrf_ids))->get()->each(function ($doc) use ($reason) {
                            $doc->status = 6;
                            $doc->reason = $reason;
                            $doc->updated_by = $this->user->id;
                            $doc->save();
                        });
                    }
                }
            }
            if (in_array((int)$update['remarks'], [5, 7], true)) {
                $data = [
                    'dispatch_no' => $dispatch->dispatch_no,
                    'awb_pod' => $dispatch->awb_pod,
                    'dispatch_date' => Carbon::parse($dispatch->dispatch_date)->format('d-m-Y'),
                    'branch_code' => $dispatch->branch_code,
                ];
                $emails = User::role(['bo-maker', 'bo-checker'])->where('branch_id', $dispatch->branch_code)->pluck('email')->toArray();
                $html = view('emails.dispatches_mail', ['data' => $data])->render();
                $subject = "Document Tracking – Courier receipt acknowledgement Dispatch ref no:#".$dispatch->dispatch_no;
                $emails = ['sridhar@netiapps.com','ragavi@netiapps.com','suraksha@netiapps.com'];
                Mail::to($emails)->send(new \App\Mail\DispatchesMail($html, $subject)); 
            } elseif ((int)$update['remarks'] === 12) {
                $data = [
                    'dispatch_no' => $dispatch->dispatch_no,
                    'awb_pod' => $dispatch->awb_pod,
                    'dispatch_date' => Carbon::parse($dispatch->dispatch_date)->format('d-m-Y'),
                    'branch_code' => $dispatch->branch_code,
                ];
                $emails = User::role(['bo-maker', 'bo-checker'])->where('branch_id', $dispatch->branch_code)->pluck('email')->toArray();
                $html = view('emails.tracking_completed', ['data' => $data])->render();
                $subject = "Document Tracking Update - Dispatch ref no:#".$dispatch->dispatch_no;
                $emails = ['sridhar@netiapps.com','ragavi@netiapps.com','suraksha@netiapps.com'];
                Mail::to($emails)->send(new \App\Mail\DispatchesMail($html, $subject));
            }

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    
    public function removeDispatchesDocument(Request $request)
    {
        $columns = [
            'loan' => 'loan_ids',
            'goldloan' => 'goldloan_ids',
            'dtrf' => 'dtrf_ids',
            'aof' => 'aof_ids',
        ];
    
        $type = $request->type;
        $docId = $request->doc_id;
        $dispatchId = $request->dispatch_id;
    
        try {
            DB::beginTransaction();
    
            $dispatch = CourierDispatch::findOrFail($dispatchId);
            $columnName = $columns[$type];
    
            // Remove the doc ID from the appropriate column
            $values = collect(explode(',', $dispatch->$columnName))
                ->map(fn($v) => trim($v))->filter(fn($v) => $v !== $docId && $v !== '')
                ->values()->implode(',');
    
            $dispatch->$columnName = $values;
            $dispatch->updated_by = $this->user->id;
            $dispatch->save();
    
            // $doc = $this->table[$type]::find('id', $docId);
            $doc = $this->table[$type]::find($docId);
            $doc->status = 1;
            $doc->dispatch_id = null;
            $doc->updated_by = $this->user->id;
            $doc->save();
    
            $allEmpty = empty($dispatch->loan_ids) && empty($dispatch->goldloan_ids) && empty($dispatch->aof_ids) && empty($dispatch->dtrf_ids);
    
            if ($allEmpty) {
                $dispatch->deleted_by = $this->user->id;
                $dispatch->save();
                $dispatch->delete(); // Laravel soft delete
            }
    
            DB::commit();
    
            return response()->json([ 'success' => true, 'dispatch_deleted' => $allEmpty ]);
    
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function updateCourierDetails(Request $request, $id)
    {
        $request->validate([
            'courier_name' => 'required',
            'mmrp_barcode' => 'required|alpha_num',
        ]);
        try {
            DB::beginTransaction();
            try {
                $decryptedId = Crypt::decryptString($id);
            } catch (DecryptException $e) {
                abort(404, 'Invalid ID');
            }
            $courier = CourierDispatch::findOrFail($decryptedId);

            $courier->courier_id = $request->courier_name;
            $courier->courier_name = $request->courier_name;
            $courier->mmrp_barcode = $request->mmrp_barcode;
            $courier->awb_pod = $request->awb_pod;
            $courier->save();
            DB::commit();
            return redirect()->route('dispatches','list')->with('success', 'Courier updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('dispatches.list')->with('error', 'Courier Not Updated');
        }
    }

    public function removeDocument(Request $request)
    {
        $docIds = $request->doc_ids;  // array of selected IDs
        $reason = $request->reason;
        try {
            DB::beginTransaction();

            // Store reason if needed (optional, if reason column exists)
            foreach ($docIds as $key => $value) {
                $this->table[$key]::whereIn('id',$this->decryptIds($value))->get()->each(function ($doc) use($reason) {
                    $doc->reason = $reason;
                    $doc->deleted_by = $this->user->id;
                    $doc->save();
                    $doc->delete(); // Laravel soft delete
                });
            }
            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function statusUpdate(Request $request)
    {
        try {
            DB::beginTransaction();
            if($request->input('updates', []))
                $updates = $request->input('updates', []);
            else
                $updates[] = $request->all();
            
            foreach ($updates as $update) {
                session(['dtype' => $update['type']]);
                $doc = $this->table[$update['type']]::find($update['id']);
                $doc->status = $update['remarks'];
                if(isset($update['reason_for_rejection']))
                    $doc->reason = $update['reason_for_rejection'];
                $doc->updated_by = $this->user->id;
                $doc->save();
            }

            DB::commit();

            if($request->input('updates', []))
                return response()->json(['success' => true]);
            else
                return redirect()->route('accounts.index',['type' => 'moved','dtype' => 'loan'])->with('success', 'Status Updated Successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            if($request->input('updates', []))
                return response()->json(['error' => $e->getMessage()], 500);
            else
                return redirect()->route('accounts.index',['type' => 'moved','dtype' => 'loan'])->with('error', 'Status Updation Failed.');
        }
    }

    public function addRmaDetails(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'lot_no' => 'nullable|string|max:255',
            'category_of_document' => 'nullable|string|max:255',
            'work_order_no' => 'nullable|string|max:255',
            'vendor_name' => 'nullable|string|max:255',
            'vendor_movement_date' => 'nullable|date|before_or_equal:today',
            'file_barcode' => 'nullable|string|max:255',
            'box_barcode' => 'nullable|string|max:255',
            'date_added_to_vendor' => 'nullable|date|before_or_equal:today',
            'status' => 'nullable',
        ]);

        try {
            DB::beginTransaction();

            $doc = $this->table[$request->dtype]::find($request->id);
            $doc->lot_no = $validated['lot_no'];
            $doc->category_of_document = $validated['category_of_document'];
            $doc->work_order_no = $validated['work_order_no'];
            $doc->vendor_name = $validated['vendor_name'];
            $doc->vendor_movement_date = Carbon::parse($validated['vendor_movement_date'])->format('Y-m-d');
            $doc->file_barcode = $validated['file_barcode'];
            $doc->box_barcode = $validated['box_barcode'];
            $doc->date_added_to_vendor = Carbon::parse($validated['date_added_to_vendor'])->format('Y-m-d');
            $doc->status = $validated['status'];
            $doc->updated_by = $this->user->id;
            $doc->save();
            DB::commit();
            if($request->ajax())
                return response()->json(['success' => true]);
            else
                return redirect()->route('accounts.index',['type' => 'moved','dtype' => $request->dtype])->with('success', 'File Moved to RMA successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function uploadVendorData(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        $import = new VendorDocumentImport();
        Excel::import($import, $request->file('excel_file'));

        if (!empty($import->failures())) {
            return redirect()->back()->with([
                'error' => 'Some rows failed to import.',
                'failures' => $import->failures(),
            ]);
        }
        
        return redirect()->back()->with('success', 'Excel uploaded successfully!');
    }
    public function viewHistory($id,$type,$dtype)
    {
        try {
            $decryptedId = Crypt::decryptString($id);
        } catch (DecryptException $e) {
            abort(404, 'Invalid ID');
        }
    
        // $history = DocumentHistory::findOrFail($decryptedId);

        $document = $this->table[$dtype]::find($decryptedId);
        if ($this->user->hasRole('bo-maker') || $this->user->hasRole('bo-checker') || $this->user->hasRole('branch-user')) {
            if($this->user->branch_id != $document->branch_code){
                return redirect('/home')->with('error', 'Access Denied');
            }
        } elseif ($this->user->hasRole('ro-officer') || $this->user->hasRole('ro-supervisor') || $this->user->hasRole('ro-user')) {
            if($this->user->region != $document->region){
                return redirect('/home')->with('error', 'Access Denied');
            }
        }
        $history = DocumentHistory::where('document_id',$decryptedId)->where('document_type',class_basename($this->table[$dtype]))->get();

        return view('accounts.doc_history', compact('document','history','dtype','type'));
    }

    public function trashedDocuments()
    {
        $start_date = Carbon::now()->subWeek()->startOfWeek(); 
        $end_date = Carbon::now()->subWeek()->endOfWeek();

        $filter = function ($query) {
            // if($type === 'moved'){
            //     $query->where('status','>=',8);
            // }
            // elseif($type === 'received'){
            //     $query->whereIn('status',[5,7]);
            // }
            // elseif($type ==='rejected'){
            //     $query->where('status',6);
            // }
            // elseif($type ==='pending'){
            //     $query->where('status',1);
            // }
            if ($this->user->hasRole('ro-officer') || $this->user->hasRole('ro-supervisor') || $this->user->hasRole('ro-user')) {
                $query->where('region', $this->user->region);
            }
            if ($this->user->hasRole('bo-maker') || $this->user->hasRole('bo-checker') || $this->user->hasRole('branch-user')) {
                $query->where('branch_code', $this->user->branch_id);
            }
            return $query->onlyTrashed()->orderBy('deleted_at', 'desc');
        };
        // dd($filter(LoanDocfiltersument::query())->tosql());
        $loan_document = $filter(LoanDocument::query())->paginate(100)->withQueryString();
        $gold_loan_document = $filter(GoldLoanDocument::query())->paginate(100)->withQueryString();
        $dtrf_document = $filter(DtrfDocument::query())->paginate(100)->withQueryString();
        $account_opening_document = $filter(AccountOpeningDocument::query())->paginate(100)->withQueryString();
        $loan_total = $loan_document->total();
        $gold_loan_total = $gold_loan_document->total();
        $dtrf_total = $dtrf_document->total();
        $aof_total = $account_opening_document->total();
        $process_statuses = ProcessStatus::where('status', 1)->get();
        $vendors = Vendor::all();

        $fixedStatuses = [
            'pending' => 1,
            'rejected' => 6,
            'received' => [5, 7],
        ];
        
        $type = 'Trashed';
        $fixed_status = $fixedStatuses[$type] ?? null;
        
        if ($fixed_status) {
            $filters['status'] = $fixed_status;
        }

        return view('accounts.trashed', compact('loan_document', 'gold_loan_document', 'dtrf_document', 'account_opening_document', 'type', 'loan_total', 'gold_loan_total', 'dtrf_total', 'aof_total', 'process_statuses', 'vendors', 'fixed_status'));
    }

    public function restoreDocument(Request $request)
    {
        try {
            DB::beginTransaction();

            $doc = $this->table[$request->type]::withTrashed()->find($request->id);
            $doc->reason = $request->reason;
            $doc->updated_by = $this->user->id;
            $doc->save();
            $doc->restore();

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // public function checkAwb(Request $request)
    // {
    //     $exists = CourierDispatch::where('awb_pod', $request->awb_pod)->exists();
    //     return response()->json(['exists' => $exists]);
    // }
    public function checkAwb(Request $request)
    {
        $exists = CourierDispatch::where('awb_pod', $request->awb_pod)
            ->where('courier_id', $request->courier_id) // match courier name too
            ->exists();

        return response()->json(['exists' => $exists]);
    }

    
    public function reports( $type)
    {
        $users = User::where('status','active')->pluck('first_name', 'id');
        $couriers = Courier::where('status','active')->pluck('name', 'id');
        $loan_branch = LoanDocument::groupby('branch_code')->orderby('branch_code','asc')->pluck('branch_code','branch_code')->toArray();
        $goldloan_branch = GoldLoanDocument::groupby('branch_code')->orderby('branch_code','asc')->pluck('branch_code','branch_code')->toArray();
        $dtrf_branch = DtrfDocument::groupby('branch_code')->orderby('branch_code','asc')->pluck('branch_code','branch_code')->toArray();
        $aof_branch = AccountOpeningDocument::groupby('branch_code')->orderby('branch_code','asc')->pluck('branch_code','branch_code')->toArray();
        $branches = $loan_branch + $goldloan_branch + $dtrf_branch + $aof_branch;
        $vendors = Vendor::all();
        $couriers = Courier::where('status', 1)->get();

        return view('accounts.reports', compact('vendors', 'couriers','branches', 'type'));
      
    }


    // public function export(Request $request)
    // {
    //     $user = $this->user;
    //     $filter = function ($query, $table) use ($user, $request) {
    //         $filters = $request->all();
    //         // dd($filters);
    //         $docType = $filters['doc_type'] ?? null;
            
    //         $fromDate = !empty($filters['from_date']) ? Carbon::parse($filters['from_date'])->startOfDay() : null;
    //         $toDate = !empty($filters['to_date']) ? Carbon::parse($filters['to_date'])->endOfDay() : Carbon::now()->endOfDay();
    //         // $date = [$fromDate, $toDate];
    //         // dd($date);
    //         foreach ($filters as $field => $value) {
    //             if (!empty($value) && \Schema::hasColumn($table, $field)) {
    //                 if (in_array($field, ['cif_id', 'account_number'])) {
    //                     $query->where($field, 'like', '%' . $value . '%');
    //                 } elseif (is_array($value)) {
    //                     $query->whereIn($field, $value);
    //                 } else {
    //                     $query->where($field, $value);
    //                 }
    //             }
    //         }

    //         // Courier name filtering
    //         if (!empty($filters['courier_name'])) {
    //             $courierId = $filters['courier_name'];
    //             $query->whereHas('dispatch.courierName', function ($q) use ($courierId) {
    //                 $q->where('id', $courierId); // if dropdown stores ID
    //                 // $q->where('name', $courierId); // if dropdown stores NAME instead
    //             });
    //         }

    //         if ($request->filled(['from_date', 'to_date', 'date_field'])) {
    //             $dateField = $request->input('date_field');
            
    //             $dispatchField = match ($dateField) {
    //                 'dispatch_date' => 'dispatch_date',
    //                 'received_date' => 'updated_at',
    //                 'tracking_date' => 'updated_at',
    //                 default         => null,
    //             };
            
    //             $mainField = match ($dateField) {
    //                 'creation_date' => 'account_creation_date',
    //                 'movement_date' => 'vendor_movement_date',
    //                 'addition_date' => 'date_added_to_vendor',
    //                 'activity_date' => 'updated_at',
    //                 'sync_date'     => 'created_at',
    //                 // 'tracking_date' => 'tracking_date',
    //                 default         => null,
    //             };

    //             // If filtering by dispatch date or received date → only dispatched docs
    //             if ($dispatchField) {
    //                 $query->whereHas('dispatch', function ($q) use ($dispatchField, $fromDate, $toDate, $dateField) {
    //                     if ($dateField == 'received_date') {
    //                         $q->whereIn('status', [5,6,7]);
    //                     } elseif ($dateField == 'tracking_date'){
    //                         $q->where('status', 12);
    //                     }
    //                     if ($fromDate && $toDate) {
    //                         $q->whereBetween($dispatchField, [$fromDate, $toDate]);
    //                     } elseif ($fromDate) {
    //                         $q->whereDate($dispatchField, '>=', $fromDate);
    //                     } elseif ($toDate) {
    //                         $q->whereDate($dispatchField, '<=', $toDate);
    //                     }
    //                 });
    //             }
                
    //             // Otherwise check both dispatched and non-dispatched
    //             elseif ($mainField) {
    //                 $query->where(function ($q) use ($mainField, $fromDate, $toDate) {
    //                     // For dispatched docs → match main field too
    //                     $q->whereHas('dispatch', function ($dq) use ($mainField, $fromDate, $toDate) {
    //                         if ($fromDate && $toDate) {
    //                             $dq->whereBetween($mainField, [$fromDate, $toDate]);
    //                         } elseif ($fromDate) {
    //                             $dq->whereDate($mainField, '>=', $fromDate);
    //                         } elseif ($toDate) {
    //                             $dq->whereDate($mainField, '<=', $toDate);
    //                         }
    //                     });
            
    //                     $q->orWhere(function ($sq) use ($mainField, $fromDate, $toDate) {
    //                         $sq->doesntHave('dispatch');
                
    //                         if ($fromDate && $toDate) {
    //                             $sq->whereBetween($mainField, [$fromDate, $toDate]);
    //                         } elseif ($fromDate) {
    //                             $sq->whereDate($mainField, '>=', $fromDate);
    //                         } elseif ($toDate) {
    //                             $sq->whereDate($mainField, '<=', $toDate);
    //                         }
    //                     });
                        
    //                 });
    //             }
    //         }
            
    //         return $query->orderBy('account_creation_date', 'desc');
    //     };

    //     if ($request->doc_type === 'loan') {
    //         $data = LoanDocument::with([
    //             'dispatch.courierName',
    //             'dispatch.dispatcher',
    //             'dispatch.modifier',
    //             'getReceivedDetails.newStatus',
    //             'getReceivedDetails.creator',
    //             'statusName',
    //         ])->select([
    //             'id', 'unique_ref_no', 'region', 'branch_code', 'branch_name',
    //             'cif_id', 'account_number', 'loan_cycle', 'customer_name',
    //             'account_creation_date', 'channel', 'status', 'reason',
    //             'vendor_name', 'vendor_movement_date', 'file_barcode', 'box_barcode',
    //             'date_added_to_vendor', 'lot_no', 'category_of_document', 'work_order_no'
    //         ]);
        
    //         $filter($data, 'loan_documents');
        
    //         return Excel::download(new LoanDocumentExport($data),"loan_documents_" . now()->format('Y_m_d_H_i_s') . ".csv",\Maatwebsite\Excel\Excel::CSV);
    //     } elseif ($request->doc_type === 'goldloan') {
    //         $data = GoldLoanDocument::query();
    //         $filter($data, 'gold_loan_documents');
    //         // dd($data->get());
    //         // foreach($data->get() as $dt){
    //         //     dd($dt->dispatch->courierName->name);
    //         // }
    //     return Excel::download(new GoldLoanDocumentExport($data), "loan_documents_" . now()->format('Y_m_d_H_i_s') . ".csv", \Maatwebsite\Excel\Excel::CSV);
    //     } elseif ($request->doc_type === 'dtrf') {
    //         $data = DtrfDocument::query();
    //         $filter($data, 'dtrf_documents');
    //         // foreach($data->get() as $dt){
    //         //     if(!empty($dt->getReceivedDetails)){
    //         //         dd($dt->getReceivedDetails->creator->first_name);
    //         //     }
    //         // }
    //         // exit;
    //         // return Excel::download(new DtrfExport($data->get()), 'dtrf_documents.xlsx');
    //     return Excel::download(new DtrfExport($data), "loan_documents_" . now()->format('Y_m_d_H_i_s') . ".csv", \Maatwebsite\Excel\Excel::CSV);
    //     } elseif ($request->doc_type === 'aof') {
    //         $data = AccountOpeningDocument::query();
    //         $filter($data, 'account_opening_documents');
    //         // dd($data->get());
    //         // foreach($data->get() as $dt){
    //         //     dd($dt->dispatch->courierName->name);
    //         // }
    //         // return Excel::download(new AccountOpeningDocumentExport($data->get()), 'account_opening_documents.xlsx');
    //     return Excel::download(new AccountOpeningDocumentExport($data), "loan_documents_" . now()->format('Y_m_d_H_i_s') . ".csv", \Maatwebsite\Excel\Excel::CSV);
    //     } else {
    //         return redirect()->back()->with('error', 'Invalid document type selected.');
    //     }
    // }

    public function export(Request $request)
    {
        $user = $this->user;
        $filters = $request->all();

        $fromDate = !empty($filters['from_date']) ? Carbon::parse($filters['from_date'])->startOfDay() : null;
        $toDate = !empty($filters['to_date']) ? Carbon::parse($filters['to_date'])->endOfDay() : Carbon::now()->endOfDay();

        // Allowed columns for filter (faster than Schema check)
        $allowedFilters = [
            'region', 'branch_code', 'status',
            'cif_id', 'account_number', 'vendor_name'
        ];

        // Shared filter closure
        $filter = function ($query, $filters, $fromDate, $toDate, $request, $table) use ($allowedFilters) {
            foreach ($filters as $field => $value) {
                if (!empty($value) && in_array($field, $allowedFilters)) {
                    if (in_array($field, ['cif_id', 'account_number'])) {
                        $query->where($field, 'like', '%' . $value . '%');
                    } elseif (is_array($value)) {
                        $query->whereIn($field, $value);
                    } else {
                        $query->where($field, $value);
                    }
                }
            }

            // courier filter
            if (!empty($filters['courier_name'])) {
                $courierId = $filters['courier_name'];
                $query->whereHas('dispatch.courierName', function ($q) use ($courierId) {
                    $q->where('id', $courierId);
                });
            }

            // date filtering
            if ($request->filled(['from_date', 'to_date', 'date_field'])) {
                $dateField = $request->input('date_field');

                $dispatchField = match ($dateField) {
                    'dispatch_date' => 'dispatch_date',
                    'received_date' => 'updated_at',
                    'tracking_date' => 'updated_at',
                    default => null,
                };

                $mainField = match ($dateField) {
                    'creation_date' => 'account_creation_date',
                    'movement_date' => 'vendor_movement_date',
                    'addition_date' => 'date_added_to_vendor',
                    'activity_date' => 'updated_at',
                    'sync_date' => 'created_at',
                    default => null,
                };

                if ($dispatchField) {
                    $query->whereHas('dispatch', function ($q) use ($dispatchField, $fromDate, $toDate, $dateField) {
                        if ($dateField == 'received_date') {
                            $q->whereIn('status', [5, 6, 7]);
                        } elseif ($dateField == 'tracking_date') {
                            $q->where('status', 12);
                        }

                        if ($fromDate && $toDate) {
                            $q->whereBetween($dispatchField, [$fromDate, $toDate]);
                        } elseif ($fromDate) {
                            $q->whereDate($dispatchField, '>=', $fromDate);
                        } elseif ($toDate) {
                            $q->whereDate($dispatchField, '<=', $toDate);
                        }
                    });
                } elseif ($mainField) {
                    $query->whereBetween($mainField, [$fromDate, $toDate]);
                }
            }

            return $query->orderBy('account_creation_date', 'desc');
        };

        if ($request->doc_type === 'loan') {
            $data = LoanDocument::with([
                'dispatch.courierName',
                'dispatch.dispatcher',
                'dispatch.modifier',
                'getReceivedDetails.newStatus',
                'getReceivedDetails.creator',
                'statusName'
            ])->select([
                'unique_ref_no', 'region', 'branch_code', 'branch_name', 'cif_id', 'account_number',
                'loan_cycle', 'customer_name', 'account_creation_date', 'channel', 'glow_application_id',
                'loan_amount', 'loan_disbursement_type', 'business_category', 'barcode', 'lot_no',
                'category_of_document', 'work_order_no', 'vendor_name', 'vendor_movement_date', 'file_barcode',
                'box_barcode', 'date_added_to_vendor', 'reason', 'status'
            ]);

            $filter($data, $filters, $fromDate, $toDate, $request, 'loan_documents');

            return Excel::download(
                new LoanDocumentExport($data),
                "loan_documents_" . now()->format('Y_m_d_H_i_s') . ".csv",
                \Maatwebsite\Excel\Excel::CSV
            );
        }

        elseif ($request->doc_type === 'goldloan') {
            $data = GoldLoanDocument::with([
                'dispatch.courierName',
                'dispatch.dispatcher',
                'dispatch.modifier',
                'getReceivedDetails.newStatus',
                'getReceivedDetails.creator',
                'statusName'
            ])->select([
                'unique_ref_no', 'region', 'branch_code', 'branch_name', 'cif_id', 'account_number',
                'customer_name', 'account_creation_date', 'channel',
                'loan_amount', 'barcode', 'lot_no', 'business_category',
                'category_of_document', 'work_order_no', 'vendor_name', 'vendor_movement_date', 'file_barcode',
                'box_barcode', 'date_added_to_vendor', 'reason', 'status'
            ]);

            $filter($data, $filters, $fromDate, $toDate, $request, 'gold_loan_documents');

            return Excel::download(
                new GoldLoanDocumentExport($data),
                "goldloan_documents_" . now()->format('Y_m_d_H_i_s') . ".csv",
                \Maatwebsite\Excel\Excel::CSV
            );
        }

        elseif ($request->doc_type === 'dtrf') {
            $data = DtrfDocument::with([
                'dispatch.courierName',
                'dispatch.dispatcher',
                'dispatch.modifier',
                'getReceivedDetails.newStatus',
                'getReceivedDetails.creator',
                'statusName'
            ])->select([
                'unique_ref_no', 'region', 'branch_code', 'branch_name',
                'account_creation_date',  'business_category', 'barcode', 'lot_no',
                'category_of_document', 'work_order_no', 'vendor_name', 'vendor_movement_date', 'file_barcode',
                'box_barcode', 'date_added_to_vendor', 'reason', 'status'
            ]);

            $filter($data, $filters, $fromDate, $toDate, $request, 'dtrf_documents');

            return Excel::download(
                new DtrfExport($data),
                "dtrf_documents_" . now()->format('Y_m_d_H_i_s') . ".csv",
                \Maatwebsite\Excel\Excel::CSV
            );
        }

        elseif ($request->doc_type === 'aof') {
            $data = AccountOpeningDocument::with([
                'dispatch.courierName',
                'dispatch.dispatcher',
                'dispatch.modifier',
                'getReceivedDetails.newStatus',
                'getReceivedDetails.creator',
                'statusName'
            ])->select([
                'unique_ref_no', 'region', 'branch_code', 'branch_name', 'cif_id', 'account_number',
                'customer_name', 'account_creation_date', 'scheme', 'channel', 'pgk_no',
                'type_of_account_opening', 'business_category', 'barcode', 'lot_no',
                'category_of_document', 'work_order_no', 'vendor_name', 'vendor_movement_date', 'file_barcode',
                'box_barcode', 'date_added_to_vendor', 'reason', 'status'
            ]);

            $filter($data, $filters, $fromDate, $toDate, $request, 'account_opening_documents');

            return Excel::download(
                new AccountOpeningDocumentExport($data),
                "aof_documents_" . now()->format('Y_m_d_H_i_s') . ".csv",
                \Maatwebsite\Excel\Excel::CSV
            );
        }

        return redirect()->back()->with('error', 'Invalid document type selected.');
    }

    public function sendEmail($subject, $content, $to, $cc=null)
    {
        $to = ['sridhar@netiapps.com','ragavi@netiapps.com','suraksha@netiapps.com'];
        Mail::to($to)->send(new \App\Mail\DispatchesMail($content, $subject));
    }

    public function revertStatus(Request $request)
    {
        $id = $request->document_id;
        $dtype = $request->dtype;
        $columns = [
            'loan' => 'loan_ids',
            'goldloan' => 'goldloan_ids',
            'dtrf' => 'dtrf_ids',
            'aof' => 'aof_ids',
        ];
        try {
            DB::beginTransaction();

            $doc = $this->table[$dtype]::find($id);
            $history = DocumentHistory::where('document_id',$id)->where('document_type',class_basename($this->table[$dtype]))
                ->where('current_status',$doc->status)->where('previous_status','<',$doc->status)->latest()->first();
            $doc->status = $history->previous_status;
            $doc->reason = $request->reason;
            $doc->updated_by = $this->user->id;
            $doc->save();
            // if($history->previous_status == '4'){
            //     $dispatch = CourierDispatch::
            // }

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function revertCourierStatus(Request $request)
    {
        try {
            DB::beginTransaction();
            try {
                $decryptedId = Crypt::decryptString($request->dispatch_id);
            } catch (DecryptException $e) {
                abort(404, 'Invalid ID');
            }
            $doc = CourierDispatch::find($decryptedId);
            $doc->status = $request->status;
            $doc->comments = $request->reason;
            $doc->updated_by = $this->user->id;
            $doc->save();

            DB::commit();

            return redirect()->route('dispatches','tracking')->with('success','Courier Reverted Successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('dispatches','tracking')->with('error',$e->getMessage());
        }
    }

    protected function decryptIds(array $encryptedIds)
    {
        return collect($encryptedIds)->map(function ($id) {
            try {
                return Crypt::decryptString($id);
            } catch (DecryptException $e) {
                return null; // ignore tampered IDs
            }
        })->filter()->toArray();
    }


    // public function test(){

    //     $branchRecords = ['1115','1116'];

    //     foreach ($branchRecords as $record) {
    //         $uniqueRefNo = AccountOpeningDocument::where('branch_code', $record)->whereMonth('created_at', now()->month)
    //             ->whereYear('created_at', now()->year)->max('unique_ref_no');
    
    //         if($uniqueRefNo == null)
    //         {
    //             $uniqueRefNo = 'LD' . str_pad($record, 4, '0', STR_PAD_LEFT) . now()->format('my') . str_pad(1, 4, '0', STR_PAD_LEFT);
    //         }
    //         else{
    //             $uniqueRefNo++;
    //         }
            
    //         // $data = $record->toArray();
    //         $data['unique_ref_no'] = $uniqueRefNo;
    //         $data['status'] = 1;
    //         dd($data);
    //         // $targetModel::create($data);
    //     }
    // }
}