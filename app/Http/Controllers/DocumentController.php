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
use Auth;
use DB;

class DocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            return $next($request);
        });
    }
    public function index(Request $request, $type)
    {
        $start_date = Carbon::now()->subWeek()->startOfWeek(); 
        $end_date = Carbon::now()->subWeek()->endOfWeek();

        // dd($type);
        // if ($this->user->hasRole('bo-maker') || $this->user->hasRole('bo-checker')) {
        //     LoanDocument::where('branch_code',$this->user->branch_id)->where('status',2)->update(['status'=>'pending']);
        //     GoldLoanDocument::where('branch_code',$this->user->branch_id)->where('status',2)->update(['status'=>'pending']);
        //     DtrfDocument::where('branch_code',$this->user->branch_id)->where('status',2)->update(['status'=>'pending']);
        //     AccountOpeningDocument::where('branch_code',$this->user->branch_id)->where('status',2)->update(['status'=>'pending']);
        // }

        $filter = function ($query) use ($type, $start_date, $end_date) {
            if($type === 'received'){
                $query->where('status',5);
            }
            elseif($type ==='rejected'){
                $query->where('status',6);
            }
            if ($this->user->hasRole('ro-user')) {
                $query->where('region', $this->user->region);
            }
            if ($this->user->hasRole('bo-maker') || $this->user->hasRole('bo-checker')) {
                $query->where('branch_code', $this->user->branch_id);
            }
            return $query
                ->when($type === 'new', function ($q) use ($start_date, $end_date) {
                    $q->whereBetween('account_creation_date', [$start_date, $end_date]);
                });
        };
        $loan_document = $filter(LoanDocument::query())->paginate(100);
        $gold_loan_document = $filter(GoldLoanDocument::query())->paginate(100);
        $dtrf_document = $filter(DtrfDocument::query())->paginate(100);
        $account_opening_document = $filter(AccountOpeningDocument::query())->paginate(100);
        $loan_total = $loan_document->total();
        $gold_loan_total = $gold_loan_document->total();
        $dtrf_total = $dtrf_document->total();
        $aof_total = $account_opening_document->total();

        return view('accounts.accounts', compact('loan_document', 'gold_loan_document', 'dtrf_document', 'account_opening_document', 'type', 'loan_total', 'gold_loan_total', 'dtrf_total', 'aof_total'));
    }
    public function filter(Request $request)
    {
        session(['filters' => $request->all()]);
        return redirect()->route('document.filtered');
    }
    
    public function filteredList()
    {
        $filters = session('filters', []);
        $user = $this->user;
        $hasFilters = collect($filters)->filter()->isNotEmpty();
        $fromDate = $filters['from_date'] ?? null;
        $toDate = $filters['to_date'] ?? null;
        // dd($filters);
        unset($filters['start_date'], $filters['end_date']);

        // $fromDate = isset($filters['start_date']) ? Carbon\Carbon::createFromFormat('d-m-Y', $filters['start_date'])->format('Y-m-d') : null;
        // $toDate = isset($filters['end_date']) ? Carbon\Carbon::createFromFormat('d-m-Y', $filters['end_date'])->format('Y-m-d') : null;

        $docType = $filters['document_type'] ?? null;
        $filterFunction = function ($query, $table) use ($user, $filters, $hasFilters,$fromDate,$toDate) {

            if ($user->hasRole('ro-user')) {
                $query->where('region', $user->region);
            }
            if ($user->hasRole('bo-maker') || $user->hasRole('bo-checker')) {
                $query->where('branch_code', $user->branch_id);
            }
            if ($fromDate != null && $toDate != null) {
                $query->whereBetween('account_creation_date', [$fromDate, $toDate]);
            } elseif ($fromDate != null) {
                $query->whereDate('created_at', '>=', $fromDate);
            } elseif ($toDate != null) {
                $query->whereDate('created_at', '<=', $toDate);
            }
        
            if ($hasFilters) {
                foreach ($filters as $field => $value) {
                    if (!empty($value) && \Schema::hasColumn($table, $field)) {
                        $query->where($field, $value);
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
            })->paginate(100);

        } elseif ($docType === 'gold_loan') {
            $gold_loan_document = GoldLoanDocument::where(function ($q) use ($filterFunction) {
                $filterFunction($q, 'gold_loan_documents');
            })->paginate(100);

        } elseif ($docType === 'dtrf') {
            $dtrf_document = DtrfDocument::where(function ($q) use ($filterFunction) {
                $filterFunction($q, 'dtrf_documents');
            })->paginate(100);

        } elseif ($docType === 'aof') {
            $account_opening_document = AccountOpeningDocument::where(function ($q) use ($filterFunction) {
                $filterFunction($q, 'account_opening_documents');
            })->paginate(100);

        } else {
            $loan_document = LoanDocument::where(function ($q) use ($filterFunction) {
                $filterFunction($q, 'loan_documents');
            })->paginate(100);
            
            $gold_loan_document = GoldLoanDocument::where(function ($q) use ($filterFunction) {
                $filterFunction($q, 'gold_loan_documents');
            })->paginate(100);
            
            $dtrf_document = DtrfDocument::where(function ($q) use ($filterFunction) {
                $filterFunction($q, 'dtrf_documents');
            })->paginate(100);
            
            $account_opening_document = AccountOpeningDocument::where(function ($q) use ($filterFunction) {
                $filterFunction($q, 'account_opening_documents');
            })->paginate(100);
        }
        $loan_total =$loan_document != null ? $loan_document->total():0;
        $gold_loan_total = $gold_loan_document != null ? $gold_loan_document->total():0;
        $dtrf_total = $dtrf_document != null ? $dtrf_document->total():0;
        $aof_total = $account_opening_document != null ? $account_opening_document->total():0;

        $type = 'all';
        return view('accounts.accounts', compact('loan_document', 'gold_loan_document', 'dtrf_document', 'account_opening_document', 'type', 'loan_total', 'gold_loan_total', 'dtrf_total', 'aof_total','filters'));
    }
    
    public function bulkReview(Request $request)
    {
        if(isset($request->loan_ids))
            LoanDocument::whereIn('id',$request->loan_ids)->update(['status'=>2]);
        if(isset($request->goldloan_ids))
            GoldLoanDocument::whereIn('id',$request->goldloan_ids)->update(['status'=>2]);
        if(isset($request->dtrf_ids))
            DtrfDocument::whereIn('id',$request->dtrf_ids)->update(['status'=>2]);
        if(isset($request->aof_ids))
            AccountOpeningDocument::whereIn('id',$request->aof_ids)->update(['status'=>2]);

        return redirect()->route('accounts.selected');
    }

    public function getBulkReview()
    {
        $allDocuments = collect();
        $loans = LoanDocument::where('branch_code', $this->user->branch_id)->where('status', 2)->get()
            ->map(function ($item) {
                $item->doc_type = 'loan';
                return $item;
            });
        $allDocuments = $allDocuments->merge($loans);
        $goldloans = GoldLoanDocument::where('branch_code', $this->user->branch_id)->where('status', 2)->get()
            ->map(function ($item) {
                $item->doc_type = 'goldloan';
                return $item;
            });
        $allDocuments = $allDocuments->merge($goldloans);
        $aofs = AccountOpeningDocument::where('branch_code', $this->user->branch_id)->where('status', 2)->get()
            ->map(function ($item) {
                $item->doc_type = 'aof';
                return $item;
            });
        $allDocuments = $allDocuments->merge($aofs);
        $dtrfs = DtrfDocument::where('branch_code', $this->user->branch_id)->where('status', 2)->get()
            ->map(function ($item) {
                $item->doc_type = 'dtrf';
                return $item;
            });
        $allDocuments = $allDocuments->merge($dtrfs);
        
        $couriers = Courier::pluck('name','id');
        return view('accounts.index', compact('allDocuments','couriers'));
    }
    public function addCourierDetails(Request $request)
    {
        $validated = $request->validate([
            'courier_name' => 'required|string',
            'awb_pod' => 'nullable|string',
            'mmrp_barcode' => 'required|string',
            'dispatch_date' => 'required|date',
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
            $dispatch->dispatched_by = Auth::user()->id;
            $dispatch->dispatch_date = $validated['dispatch_date'];
            $dispatch->loan_ids= isset($validated['loan_ids']) ? implode(',', $validated['loan_ids']):null;
            $dispatch->goldloan_ids= isset($validated['goldloan_ids']) ? implode(',', $validated['goldloan_ids']):null;
            $dispatch->dtrf_ids= isset($validated['dtrf_ids']) ? implode(',', $validated['dtrf_ids']):null;
            $dispatch->aof_ids= isset($validated['aof_ids']) ? implode(',', $validated['aof_ids']):null;
            $dispatch->status = 3;
            $dispatch->save();

            if(isset($request->loan_ids))
                LoanDocument::whereIn('id',$request->loan_ids)->update(['status'=>3]);
            if(isset($request->goldloan_ids))
                GoldLoanDocument::whereIn('id',$request->goldloan_ids)->update(['status'=>3]);
            if(isset($request->dtrf_ids))
                DtrfDocument::whereIn('id',$request->dtrf_ids)->update(['status'=>3]);
            if(isset($request->aof_ids))
                AccountOpeningDocument::whereIn('id',$request->aof_ids)->update(['status'=>3]);
            
            DB::commit();
            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
        
    }

    public function getDispatches($type)
    {
        $filter = function ($query) use ($type) {
            if ($this->user->hasRole('ro-user')) {
                $query->where('region_id', $this->user->region_id);
            }
            if ($this->user->hasRole('bo-maker') || $this->user->hasRole('bo-checker')) {
                $query->where('branch_code', $this->user->branch_id);
            }   
            return $query
                ->when($type === 'ready', function ($q){
                    $q->where('status', 3);
                })
                ->when($type === 'list', function ($q){
                    $q->where('status', 4);
                })
                ->when($type === 'received', function ($q){
                    $q->where('status',5);
                });
        };

        $records = $filter(CourierDispatch::query())->paginate(100);

        $ready_to_dispatch_count = $filter(CourierDispatch::query())->where('status',3)->count();
        $dispatched_count = $filter(CourierDispatch::query())->where('status',4)->count();
        $received_count = $filter(CourierDispatch::query())->where('status',5)->count();

        return view('accounts.dispatches', compact('ready_to_dispatch_count','dispatched_count','type','records','received_count'));
    }

    public function viewDispatches($id)
    {
        $dispatch = CourierDispatch::find($id);
        $type = 'dispatch';

        $loan_document = LoanDocument::whereIn('id', explode(',', $dispatch->loan_ids))->paginate(100);
        $gold_loan_document = GoldLoanDocument::whereIn('id', explode(',', $dispatch->goldloan_ids))->paginate(100);
        $dtrf_document = DtrfDocument::whereIn('id', explode(',', $dispatch->dtrf_ids))->paginate(100);
        $account_opening_document = AccountOpeningDocument::whereIn('id', explode(',', $dispatch->aof_ids))->paginate(100);
        $loan_total = $loan_document->total();
        $gold_loan_total = $gold_loan_document->total();
        $dtrf_total = $dtrf_document->total();
        $aof_total = $account_opening_document->total();

        return view('accounts.dispatches_view', compact('loan_document', 'gold_loan_document', 'dtrf_document', 'account_opening_document', 'type', 'loan_total', 'gold_loan_total', 'dtrf_total', 'aof_total','dispatch'));
    }
    // public function viewDispatches($id)
    // {
    //     $dispatch = CourierDispatch::find($id);
    //     $type = 'dispatch';

    //     $allDocuments = collect(); 
    //     if (isset($dispatch->loan_ids)) {
    //         $loans = LoanDocument::whereIn('id', explode(',', $dispatch->loan_ids))->get()
    //                     ->map(function ($item) {
    //                         $item->doc_type = 'loan';
    //                         return $item;
    //                     });
    //         $allDocuments = $allDocuments->merge($loans);
    //     }
    //     if (isset($dispatch->goldloan_ids)) {
    //         $goldloans = GoldLoanDocument::whereIn('id', explode(',', $dispatch->goldloan_ids))->get()
    //                     ->map(function ($item) {
    //                         $item->doc_type = 'goldloan';
    //                         return $item;
    //                     });
    //         $allDocuments = $allDocuments->merge($goldloans);
    //     }
    //     if (isset($dispatch->dtrf_ids)) {
    //         $dtrfs = DtrfDocument::whereIn('id', explode(',', $dispatch->dtrf_ids))->get()
    //                     ->map(function ($item) {
    //                         $item->doc_type = 'dtrf';
    //                         return $item;
    //                     });
    //         $allDocuments = $allDocuments->merge($dtrfs);
    //     }
    //     if (isset($dispatch->aof_ids)) {
    //         $aofs = AccountOpeningDocument::whereIn('id', explode(',', $dispatch->aof_ids))->get()
    //                     ->map(function ($item) {
    //                         $item->doc_type = 'aof';
    //                         return $item;
    //                     });
    //         $allDocuments = $allDocuments->merge($aofs);
    //     }

    //     return view('accounts.view', compact('allDocuments','type','dispatch'));
    // }

    public function updateCourier(Request $request)
    {
        $validated = $request->validate([
            'readytodispatch_ids' => 'required|array'
        ]);
        DB::beginTransaction(); // Start Transaction

        try {
            $sequence = CourierDispatch::whereNotNull('dispatch_no')->whereDate('created_at', now()->format('Y-m-d'))->count();
            $dispatched = CourierDispatch::whereIn('id',$validated['readytodispatch_ids'])->get();
            $dispatchNumbers = [];
            foreach ($dispatched as $dispatch) {
                $sequence++;
                $dispatch->status = 4;
                $dispatch->dispatch_no = $this->buildDispatchNumber($this->user->branch_id, $dispatch->courier_name, $sequence,now());
                $dispatch->save();
                if($dispatch->loan_ids != null)
                LoanDocument::whereIn('id',explode(',', $dispatch->loan_ids))->update(['status'=>4]);
                if($dispatch->goldloan_ids != null)
                    GoldLoanDocument::whereIn('id',explode(',', $dispatch->goldloan_ids))->update(['status'=>4]);
                if($dispatch->dtrf_ids != null)
                    DtrfDocument::whereIn('id',explode(',', $dispatch->dtrf_ids))->update(['status'=>4]);
                if($dispatch->aof_ids != null)
                    AccountOpeningDocument::whereIn('id',explode(',', $dispatch->aof_ids))->update(['status'=>4]);

                $dispatchNumbers[] = '#'.$dispatch->dispatch_no;
            }
            // dd($dispatchNumbers);
            DB::commit();
            return redirect()->route('dispatches', 'list')->with('success', implode(', ', $dispatchNumbers) . ' Couriers Dispatched Successfully.');
            // return redirect()->route('dispatches','list')->with('success', '<b>' . implode(', ', $dispatchNumbers) . '</b><br>Couriers Dispatched Successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }

    }

    public function buildDispatchNumber($branchCode, $courierSlug, $sequence, $date)
    {
        $day = $date->format('d');
        $month = $date->format('m');
        $year = $date->format('y');
        $seqStr = str_pad($sequence, 3, '0', STR_PAD_LEFT);

        return "{$branchCode}{$courierSlug}{$day}{$month}{$year}{$seqStr}";
    }

    public function dispatchDetails(Request $request)
    {
        $validated = $request->validate([
            'courier_received_date' => 'required|date',
            // 'tracked_by' => 'required|string',
            'remarks' => 'required|string',
            'reason_for_rejection' => 'nullable|string',
            'loan_ids' => 'nullable|array',
            'goldloan_ids' => 'nullable|array',
            'dtrf_ids' => 'nullable|array',
            'aof_ids' => 'nullable|array',
        ]);
        // dd($request->all());

        try {
            DB::beginTransaction();

            $updateData = [
                // 'courier_received_date' => $validated['courier_received_date'],
                // 'tracked_by' => $this->user->id,
                'updated_by' => $this->user->id,
                'status' => $validated['remarks'],
                'reason' => $validated['reason_for_rejection'] ?? null
            ];

            if (!empty($validated['loan_ids'])) {
                LoanDocument::whereIn('id', $validated['loan_ids'])->update($updateData);
            }
            if (!empty($validated['goldloan_ids'])) {
                GoldLoanDocument::whereIn('id', $validated['goldloan_ids'])->update($updateData);
            }
            if (!empty($validated['dtrf_ids'])) {
                DtrfDocument::whereIn('id', $validated['dtrf_ids'])->update($updateData);
            }
            if (!empty($validated['aof_ids'])) {
                AccountOpeningDocument::whereIn('id', $validated['aof_ids'])->update($updateData);
            }

            $dispatch = CourierDispatch::find($request->dispatch_id);
            $dispatch->status = "Delivered";
            $dispatch->updated_by = $this->user->id;
            $dispatch->save();

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function removeDocument(Request $request)
    {
        $table = [
            'loan' => LoanDocument::class,
            'goldloan' => GoldLoanDocument::class,
            'dtrf' => DtrfDocument::class,
            'aof' => AccountOpeningDocument::class,
        ];

        $column = [
            'loan' => 'loan_ids',
            'goldloan' => 'goldloan_ids',
            'dtrf' => 'dtrf_ids',
            'aof' => 'aof_ids',
        ];

        // dd($table[$request->type]);
        try {
            DB::beginTransaction();
            
            $dispatch = CourierDispatch::find($request->id);
            $columnName = $column[$request->type];

            $values = collect(explode(',', $dispatch->$columnName))
                ->map(fn($v) => trim($v))
                ->filter(fn($v) => $v !== $request->doc_id)
                ->values()
                ->implode(',');
            
            $dispatch->$columnName = $values;
            $dispatch->updated_by = $this->user->id;
            $dispatch->save();

            $table[$request->type]::where('id', $request->doc_id)->update(['status'=>2]);
            
            // if(isset($request->type) && $request->type == 'loan')
            //     LoanDocument::where('id', $request->id)->update(['status'=>2]);
            // elseif(isset($request->type) && $request->type == 'goldloan')
            //     GoldLoanDocument::where('id', $request->id)->update(['status'=>2]);
            // elseif(isset($request->type) && $request->type == 'dtrf')
            //     DtrfDocument::where('id', $request->id)->update(['status'=>2]);
            // elseif(isset($request->type) && $request->type == 'aof')
            //     AccountOpeningDocument::where('id', $request->id)->update(['status'=>2]);

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}