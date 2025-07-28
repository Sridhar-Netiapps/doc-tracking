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
    public function index(Request $request, $type)
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
                if ($this->user->hasRole('bo-maker') || $this->user->hasRole('bo-checker')) {
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
            if ($this->user->hasRole('ro-user')) {
                $query->where('region', $this->user->region);
            }
            if ($this->user->hasRole('bo-maker') || $this->user->hasRole('bo-checker')) {
                $query->where('branch_code', $this->user->branch_id);
            }
            return $query->orderBy('updated_at', 'desc');
        };
        // dd($filter(GoldLoanDocument::query())->get());
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
        
        $type = $request->input('doc_type', $type ?? null);
        $fixed_status = $fixedStatuses[$type] ?? null;
        
        if ($fixed_status) {
            $filters['status'] = $fixed_status;
        }

        if($type != 'moved')
            return view('accounts.accounts', compact('loan_document', 'gold_loan_document', 'dtrf_document', 'account_opening_document', 'type', 'loan_total', 'gold_loan_total', 'dtrf_total', 'aof_total', 'process_statuses', 'vendors', 'fixed_status'));
        else
            return view('accounts.vendor_view', compact('loan_document', 'gold_loan_document', 'dtrf_document', 'account_opening_document', 'type', 'loan_total', 'gold_loan_total', 'dtrf_total', 'aof_total', 'vendors'));
    }
    public function filter(Request $request)
    {
        session(['filters' => $request->all()]);
        // return redirect()->route('document.filtered');
        $previousUrl = url()->previous(); 
        $type = Str::afterLast($previousUrl, '/'); 
        // dd($request->all());   
        // $type = $request->segment(2); 
        // if($type == 'proceed')
        if ($type == 'proceed')
            return redirect()->route('accounts.selected');
        else
            return redirect()->route('document.filtered');
    }
    
    public function filteredList(Request $request)
    {
        // $filters = session('filters', []);
        $filters = session()->pull('filters', []);
        if(empty($filters))
            return redirect()->route('accounts.index','all');
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
        $filterFunction = function ($query, $table) use ($user, $filters, $hasFilters,$fromDate,$toDate, $docType) {

            if ($user->hasRole('ro-user')) {
                $query->where('region', $user->region);
            }
            if ($user->hasRole('bo-maker') || $user->hasRole('bo-checker')) {
                $query->where('branch_code', $user->branch_id);
            }
            if ($fromDate !== null && $toDate !== null) {
                $start = Carbon::parse($fromDate)->startOfDay();
                $end   = Carbon::parse($toDate)->endOfDay();
                $query->whereBetween('updated_at', [$start, $end]);
            }
            elseif ($fromDate !== null) {
                $start = Carbon::parse($fromDate)->startOfDay();
                $query->where('updated_at', '>=', $start);
            }
            elseif ($toDate !== null) {
                $end = Carbon::parse($toDate)->endOfDay();
                $query->where('updated_at', '<=', $end);
            }
            if (isset($filters['doc_type']) && $filters['doc_type'] === 'moved') {
                $query->whereIn('status', [8, 9, 10, 11]);
            }
        
            if ($hasFilters) {
                foreach ($filters as $field => $value) {
                    if (!empty($value) && \Schema::hasColumn($table, $field)) {
                        if ($filters['doc_type'] === 'moved' && $field === 'status') {
                            continue;
                        }
                        if (in_array($field, ['cif_id', 'account_number'])) {
                            $query->where($field, 'like', '%' . $value . '%');
                        } else {
                            $query->where($field, $value);
                        }
                    }
                }
            }
            
        };

        $loan_document = null;
        $gold_loan_document = null;
        $dtrf_document = null;
        $account_opening_document = null;

        if ($docType === 'loan') {
            $loan_document = LoanDocument::where(function ($q) use ($filterFunction) {
                $filterFunction($q, 'loan_documents');
            })->paginate(100)->withQueryString();

        } elseif ($docType === 'gold_loan') {
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
            
            $dtrf_document = DtrfDocument::where(function ($q) use ($filterFunction) {
                $filterFunction($q, 'dtrf_documents');
            })->paginate(100)->withQueryString();
            
            $account_opening_document = AccountOpeningDocument::where(function ($q) use ($filterFunction) {
                $filterFunction($q, 'account_opening_documents');
            })->paginate(100)->withQueryString();
        }
        $loan_total =$loan_document != null ? $loan_document->total():0;
        $gold_loan_total = $gold_loan_document != null ? $gold_loan_document->total():0;
        $dtrf_total = $dtrf_document != null ? $dtrf_document->total():0;
        $aof_total = $account_opening_document != null ? $account_opening_document->total():0;
        $process_statuses = ProcessStatus::where('status', 1)->get();
        $type = $filters['doc_type'];
        $fixedStatuses = [
            'pending' => 1,
            'rejected' => 6,
            'received' => [5, 7],
            
        ];
        
        $type = $request->input('doc_type', $type ?? null);
        $fixed_status = $fixedStatuses[$type] ?? null;
        
        // if ($fixed_status) {
        if (!empty($fixed_status) && !is_array($fixed_status)) {
            $filters['status'] = $fixed_status;
        }
        $vendors = Vendor::all();

        if($type != 'moved')
        return view('accounts.accounts', compact('loan_document', 'gold_loan_document', 'dtrf_document', 'account_opening_document', 'type', 'loan_total', 'gold_loan_total', 'dtrf_total', 'aof_total','filters', 'process_statuses', 'vendors', 'fixed_status' ));
        else
        return view('accounts.vendor_view', compact('loan_document', 'gold_loan_document', 'dtrf_document', 'account_opening_document', 'type', 'loan_total', 'gold_loan_total', 'dtrf_total', 'aof_total','filters', 'process_statuses', 'vendors' ));
    }
    
    public function bulkReview(Request $request)
    {
        if(isset($request->loan_ids)){
            LoanDocument::whereIn('id',$request->loan_ids)->get()->each(function ($doc) {
                $doc->status = 2;
                $doc->updated_by = $this->user->id;
                $doc->save();
            });
        }
        if(isset($request->goldloan_ids)){
            GoldLoanDocument::whereIn('id',$request->goldloan_ids)->get()->each(function ($doc) {
                $doc->status = 2;
                $doc->updated_by = $this->user->id;
                $doc->save();
            });
        }
        if(isset($request->dtrf_ids)){
            DtrfDocument::whereIn('id',$request->dtrf_ids)->get()->each(function ($doc) {
                $doc->status = 2;
                $doc->updated_by = $this->user->id;
                $doc->save();
            });
        }
        if(isset($request->aof_ids)){
            AccountOpeningDocument::whereIn('id',$request->aof_ids)->get()->each(function ($doc) {
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
            if ($user->hasRole('ro-user')) {
                $query->where('region', $user->region);
            }
    
            if ($user->hasRole('bo-maker') || $user->hasRole('bo-checker')) {
                $query->where('branch_code', $user->branch_id);
            }
    
            if ($fromDate !== null && $toDate !== null) {
                $start = Carbon::parse($fromDate)->startOfDay();
                $end   = Carbon::parse($toDate)->endOfDay();
                $query->whereBetween('updated_at', [$start, $end]);
            }
            elseif ($fromDate !== null) {
                $start = Carbon::parse($fromDate)->startOfDay();
                $query->where('updated_at', '>=', $start);
            }
            elseif ($toDate !== null) {
                $end = Carbon::parse($toDate)->endOfDay();
                $query->where('updated_at', '<=', $end);
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
            if ($user->hasRole('bo-maker') || $user->hasRole('bo-checker')) {
                $query->where('branch_code', $user->branch_id);
            }
            return $query->where('status', 2)->orderBy('updated_at', 'desc');
        };

        $docType = $filters['document_type'] ?? null;
        if (!$docType || $docType === 'loan') {
            $loanQuery = LoanDocument::query();
            $customFilter($loanQuery, 'loan_documents');
            $loans = $statusFilter($loanQuery)->get()
                ->map(function ($item) {
                    $item->doc_type = 'loan';
                    return $item;
                });
            $allDocuments = $allDocuments->merge($loans);
        }
        
        if (!$docType || $docType === 'goldloan') {
            $goldQuery = GoldLoanDocument::query();
            $customFilter($goldQuery, 'gold_loan_documents');
            $goldloans = $statusFilter($goldQuery)->get()
                ->map(function ($item) {
                    $item->doc_type = 'goldloan';  // match with select value
                    return $item;
                });
            $allDocuments = $allDocuments->merge($goldloans);
        }
        
        if (!$docType || $docType === 'aof') {
            $aofQuery = AccountOpeningDocument::query();
            $customFilter($aofQuery, 'account_opening_documents');
            $aofs = $statusFilter($aofQuery)->get()
                ->map(function ($item) {
                    $item->doc_type = 'aof';
                    return $item;
                });
            $allDocuments = $allDocuments->merge($aofs);
        }
        
        if (!$docType || $docType === 'dtrf') {
            $dtrfQuery = DtrfDocument::query();
            $customFilter($dtrfQuery, 'dtrf_documents');
            $dtrfs = $statusFilter($dtrfQuery)->get()
                ->map(function ($item) {
                    $item->doc_type = 'dtrf';
                    return $item;
                });
            $allDocuments = $allDocuments->merge($dtrfs);
        }

        $process_statuses = ProcessStatus::where('status', 1)->get();
        $couriers = Courier::pluck('name', 'id');
    
         return view('accounts.index', compact('allDocuments', 'couriers', 'process_statuses', 'filters'));
    }
    
    

    public function addCourierDetails(Request $request)
    {
        $validated = $request->validate([
            'courier_name' => 'required|string',
            'awb_pod' => 'nullable|string',
            'mmrp_barcode' => 'required|string',
            // 'dispatch_date' => 'required|date',
            'loan_ids'=> 'nullable|array',
            'goldloan_ids'=> 'nullable|array',
            'dtrf_ids'=> 'nullable|array',
            'aof_ids'=> 'nullable|array'
        ]);

        DB::beginTransaction(); // Start Transaction

        try {
            $dispatch = new CourierDispatch;
            $dispatch->courier_id = $validated['courier_name'];
            $dispatch->courier_name = $validated['courier_name'];
            $dispatch->awb_pod = $validated['awb_pod'];
            $dispatch->mmrp_barcode = $validated['mmrp_barcode']; 
            $dispatch->branch_code = $this->user->branch_id; 
            $dispatch->region_id = $this->user->region_id; 
            $dispatch->loan_ids= isset($validated['loan_ids']) ? implode(',', $validated['loan_ids']):null;
            $dispatch->goldloan_ids= isset($validated['goldloan_ids']) ? implode(',', $validated['goldloan_ids']):null;
            $dispatch->dtrf_ids= isset($validated['dtrf_ids']) ? implode(',', $validated['dtrf_ids']):null;
            $dispatch->aof_ids= isset($validated['aof_ids']) ? implode(',', $validated['aof_ids']):null;
            $dispatch->status = 3;
            $dispatch->created_by = $this->user->id;
            $dispatch->save();
            $dispatch_id = $dispatch->id;

            if(isset($request->loan_ids)){
                LoanDocument::whereIn('id',$request->loan_ids)->get()->each(function ($doc) use($dispatch_id) {
                    $doc->status = 3;
                    $doc->dispatch_id = $dispatch_id;
                    $doc->updated_by = $this->user->id;
                    $doc->save();
                });
            }
            if(isset($request->goldloan_ids)){
                GoldLoanDocument::whereIn('id',$request->goldloan_ids)->get()->each(function ($doc) use($dispatch_id) {
                    $doc->status = 3;
                    $doc->dispatch_id = $dispatch_id;
                    $doc->updated_by = $this->user->id;
                    $doc->save();
                });
            }
            if(isset($request->dtrf_ids)){
                DtrfDocument::whereIn('id',$request->dtrf_ids)->get()->each(function ($doc) use($dispatch_id) {
                    $doc->status = 3;
                    $doc->dispatch_id = $dispatch_id;
                    $doc->updated_by = $this->user->id;
                    $doc->save();
                });
            }
            if(isset($request->aof_ids)){
                AccountOpeningDocument::whereIn('id',$request->aof_ids)->get()->each(function ($doc) use($dispatch_id) {
                    $doc->status = 3;
                    $doc->dispatch_id = $dispatch_id;
                    $doc->updated_by = $this->user->id;
                    $doc->save();
                });
            }
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
                $hasStatus4 = $hasStatus4 || LoanDocument::whereIn('id', explode(',', $dispatch->loan_ids))
                                ->where('status', 4)->exists();
            }

            if (!empty($dispatch->goldloan_ids)) {
                $hasStatus4 = $hasStatus4 || GoldLoanDocument::whereIn('id', explode(',', $dispatch->goldloan_ids))
                                ->where('status', 4)->exists();
            }

            if (!empty($dispatch->aof_ids)) {
                $hasStatus4 = $hasStatus4 || AccountOpeningDocument::whereIn('id', explode(',', $dispatch->aof_ids))
                                ->where('status', 4)->exists();
            }

            if (!empty($dispatch->dtrf_ids)) {
                $hasStatus4 = $hasStatus4 || DtrfDocument::whereIn('id', explode(',', $dispatch->dtrf_ids))
                                ->where('status', 4)->exists();
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
            if ($this->user->hasRole('ro-user')) {
                $query->where('region_id', $this->user->region_id);
            }

            if ($this->user->hasRole('bo-maker') || $this->user->hasRole('bo-checker')) {
                $query->where('branch_code', $this->user->branch_id);
            }
            if (!empty($filters['courier'])) {
                $query->where('courier_id', $filters['courier']);
            }
            
            if ($dispatchDate != null) {
                $query->whereDate('dispatch_date', $dispatchDate);
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

    public function viewDispatches($id)
    {
        $dispatch = CourierDispatch::find($id);
        // $type = 'dispatch';
        $previousUrl = url()->previous(); 
        $type = Str::afterLast($previousUrl, '/');

        $loan_document = LoanDocument::whereIn('id', explode(',', $dispatch->loan_ids))->paginate(100)->withQueryString();
        $gold_loan_document = GoldLoanDocument::whereIn('id', explode(',', $dispatch->goldloan_ids))->paginate(100)->withQueryString();
        $dtrf_document = DtrfDocument::whereIn('id', explode(',', $dispatch->dtrf_ids))->paginate(100)->withQueryString();
        $account_opening_document = AccountOpeningDocument::whereIn('id', explode(',', $dispatch->aof_ids))->paginate(100)->withQueryString();
        $loan_total = $loan_document->total();
        $gold_loan_total = $gold_loan_document->total();
        $dtrf_total = $dtrf_document->total();
        $aof_total = $account_opening_document->total();

        return view('accounts.dispatches_view', compact('dispatch', 'loan_document', 'gold_loan_document', 'dtrf_document', 'account_opening_document', 'type', 'loan_total', 'gold_loan_total', 'dtrf_total', 'aof_total'));
    }
    

    public function updateCourier(Request $request)
    {
        $validated = $request->validate([
            'readytodispatch_ids' => 'required|array'
        ]);
        DB::beginTransaction(); // Start Transaction

        try {
            $sequence = CourierDispatch::whereNotNull('dispatch_no')->whereDate('created_at', now()->format('Y-m-d'))->count();
            // dd($sequence);
            // $sequence = CourierDispatch::where('branch_code',$this->user->branch_id)->whereNotNull('dispatch_no')->
            // ->whereDate('created_at', now()->format('Y-m-d'))->first();
            // dd($sequence);
            $dispatched = CourierDispatch::whereIn('id',$validated['readytodispatch_ids'])->get();
            $dispatchNumbers = [];
            foreach ($dispatched as $dispatch) {
                $sequence++;
                $dispatch->verified_by = Auth::user()->id;
                $dispatch->dispatch_date = date('Y-m-d');
                $dispatch->status = 4;
                // dd($this->buildDispatchNumber($this->user->branch_id, $sequence,now()));
                $dispatch->dispatch_no = $this->buildDispatchNumber($this->user->branch_id, $sequence,now());
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
                $data = [
                    'dispatch_no' => $dispatch->dispatch_no,
                    'awb_pod' => $dispatch->awb_pod,
                    'dispatch_date' => Carbon::parse($dispatch->dispatch_date)->format('d-m-Y'),
                    'branch_code' => $dispatch->branch_code,
                ];
                $html = view('emails.dispatches_mail', ['data' => $data])->render();
                $subject = "Document Tracking – Courier receipt acknowledgement Dispatch ref no:#".$dispatch->dispatch_no;
                $emails = ['sridhar@netiapps.com','ragavi@netiapps.com','suraksha@netiapps.com'];
                Mail::to($emails)->send(new \App\Mail\DispatchesMail($html, $subject));
            }
            DB::commit();
            return redirect()->route('dispatches', 'list')->with('success', implode(', ', $dispatchNumbers) . ' Couriers Dispatched Successfully.');
            // return redirect()->route('dispatches','list')->with('success', '<b>' . implode(', ', $dispatchNumbers) . '</b><br>Couriers Dispatched Successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
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

                if (!$dispatch) continue;

                $dispatch->status = $update['remarks'];
                $dispatch->comments = $update['reason_for_rejection'];
                $dispatch->updated_by = $this->user->id;
                $dispatch->save();

                if ((int)$update['remarks'] === 6) {
                    if (!empty($dispatch->loan_ids)) {
                        LoanDocument::whereIn('id', explode(',', $dispatch->loan_ids))->get()->each(function ($doc) {
                            $doc->status = 6;
                            $doc->updated_by = $this->user->id;
                            $doc->save();
                        });
                    }
                    if (!empty($dispatch->goldloan_ids)) {
                        GoldLoanDocument::whereIn('id', explode(',', $dispatch->goldloan_ids))->get()->each(function ($doc) {
                            $doc->status = 6;
                            $doc->updated_by = $this->user->id;
                            $doc->save();
                        });
                    }
                    if (!empty($dispatch->aof_ids)) {
                        AccountOpeningDocument::whereIn('id', explode(',', $dispatch->aof_ids))->get()->each(function ($doc) {
                            $doc->status = 6;
                            $doc->updated_by = $this->user->id;
                            $doc->save();
                        });
                    }
                    if (!empty($dispatch->dtrf_ids)) {
                        DtrfDocument::whereIn('id', explode(',', $dispatch->dtrf_ids))->get()->each(function ($doc) {
                            $doc->status = 6;
                            $doc->updated_by = $this->user->id;
                            $doc->save();
                        });
                    }
                }
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
                $dispatch->delete(); 
            }
    
            DB::commit();
    
            return response()->json([ 'success' => true, 'dispatch_deleted' => $allEmpty ]);
    
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
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
                $this->table[$key]::whereIn('id',$value)->get()->each(function ($doc) use($reason) {
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
                return redirect()->route('accounts.index','moved')->with('success', 'Status Updated Successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            if($request->input('updates', []))
                return response()->json(['error' => $e->getMessage()], 500);
            else
                return redirect()->route('accounts.index','moved')->with('error', 'Status Updation Failed.');
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

            $doc = $this->table[$request->type]::find($request->id);
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

            return redirect()->back()->with('success', 'File Moved to RMA successfully!');
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
        $document = $this->table[$dtype]::find($id);
        $history = DocumentHistory::where('document_id',$id)->where('document_type',class_basename($this->table[$dtype]))->get();

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
            if ($this->user->hasRole('ro-user')) {
                $query->where('region', $this->user->region);
            }
            if ($this->user->hasRole('bo-maker') || $this->user->hasRole('bo-checker')) {
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


    public function reports(Request $request)
    {
        $users = User::where('status','active')->pluck('first_name', 'id');
        $couriers = Courier::where('status','active')->pluck('name', 'id');
        
        return view('accounts.reports');        
    }


    public function export(Request $request)
    {
        $filters = $request->all();
        
        $user = auth()->user();
        $docType = $filters['doc_type'] ?? null;

        // Format dates
        $fromDate = !empty($filters['from_date']) ? Carbon::parse($filters['from_date'])->startOfDay() : null;
        $toDate = !empty($filters['to_date']) ? Carbon::parse($filters['to_date'])->endOfDay() : null;

        // Common filter logic closure
        $filterFunction = function ($query, $table) use ($user, $filters, $fromDate, $toDate) {
            if ($fromDate && $toDate) {
                $query->whereBetween('account_creation_date', [$fromDate, $toDate]);
            } elseif ($fromDate) {
                $query->whereDate('created_at', '>=', $fromDate);
            } elseif ($toDate) {
                $query->whereDate('created_at', '<=', $toDate);
            }

            if (isset($filters['doc_type']) && $filters['doc_type'] === 'moved') {
                $query->whereIn('status', [8, 9, 10, 11]);
            }

            foreach ($filters as $field => $value) {
                if (!empty($value) && \Schema::hasColumn($table, $field)) {
                    if ($filters['doc_type'] === 'moved' && $field === 'status') {
                        continue;
                    }
                    if (in_array($field, ['cif_id', 'account_number'])) {
                        $query->where($field, 'like', '%' . $value . '%');
                    } else {
                        $query->where($field, $value);
                    }
                }
            }
        };

        switch ($docType) {
            case 'loan':
                return Excel::download(new LoanDocumentExport($filterFunction), 'loan_documents.xlsx');

            case 'goldloan':
                return Excel::download(new GoldLoanDocumentExport($filterFunction), 'gold_loan_documents.xlsx');

            case 'dtrf':
                return Excel::download(new DtrfExport($filterFunction), 'dtrf_documents.xlsx');

            case 'aof':
                return Excel::download(new AccountOpeningDocumentExport($filterFunction), 'account_opening_documents.xlsx');

            default:
                return redirect()->back()->with('error', 'Invalid document type selected.');
        }
    }

}