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

        $filter = function ($query) use ($type, $start_date, $end_date) {
            $query->where('status','pending');
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
        $user = Auth::user();
        $docType = $request->input('doc_type');

        $filters = [
            'unique_number'          => $request->input('unique_number'),
            'region'                 => $request->input('region'),
            'branch_code'            => $request->input('branch_code'),
            'branch_name'            => $request->input('branch_name'),
            'account_creation_date'  => $request->input('account_creation_date'),
            'business_category'      => $request->input('business_category'),
            'status'                 => $request->input('status'),
            'cif_id'                 => $request->input('cif_id'),
            'account_number'         => $request->input('account_number'),
            'customer_name'          => $request->input('customer_name'),
            'channel'                => $request->input('channel'),
            'loan_disbursement_type' => $request->input('loan_disbursement_type'),
            'account_opening_type'   => $request->input('account_opening_type'),
            'loan_cycle'             => $request->input('loan_cycle'),
            'scheme'                 => $request->input('scheme'),
        ];

        $filterFunction = function ($query, $table) use ($user, $filters) {
            $query->where('status', 'pending');

            if ($user->hasRole('ro-user')) {
                $query->where('region', $user->region);
            }

            if ($user->hasRole('bo-maker') || $user->hasRole('bo-checker')) {
                $query->where('branch_code', $user->branch_id);
            }

            // Apply filters dynamically
            foreach ($filters as $field => $value) {
                if (!empty($value)) {
                    if (\Schema::hasColumn($table, $field)) {
                        $query->where($field, $value);
                    }
                }
            }
        };

        $results = [];

        if ($docType === 'loan') {
            $results['loan_documents'] = LoanDocument::query()->where(function ($q) use ($filterFunction) {
                $filterFunction($q, 'loan_documents');
            })->paginate(100);

        } elseif ($docType === 'gold_loan') {
            $results['gold_loan_documents'] = GoldLoanDocument::query()->where(function ($q) use ($filterFunction) {
                $filterFunction($q, 'gold_loan_documents');
            })->paginate(100);

        } elseif ($docType === 'dtrf') {
            $results['dtrf_documents'] = DtrfDocument::query()->where(function ($q) use ($filterFunction) {
                $filterFunction($q, 'dtrf_documents');
            })->paginate(100);

        } elseif ($docType === 'aof') {
            $results['account_opening_documents'] = AccountOpeningDocument::query()->where(function ($q) use ($filterFunction) {
                $filterFunction($q, 'account_opening_documents');
            })->paginate(100);

        } else {
            $results['loan_documents'] = LoanDocument::query()->where(function ($q) use ($filterFunction) {
                $filterFunction($q, 'loan_documents');
            })->paginate(100);

            $results['gold_loan_documents'] = GoldLoanDocument::query()->where(function ($q) use ($filterFunction) {
                $filterFunction($q, 'gold_loan_documents');
            })->paginate(100);

            $results['dtrf_documents'] = DtrfDocument::query()->where(function ($q) use ($filterFunction) {
                $filterFunction($q, 'dtrf_documents');
            })->paginate(100);

            $results['account_opening_documents'] = AccountOpeningDocument::query()->where(function ($q) use ($filterFunction) {
                $filterFunction($q, 'account_opening_documents');
            })->paginate(100);
        }

        return response()->json($results);
    }
    
    public function bulkReview(Request $request)
    {
        if(isset($request->loan_ids))
            LoanDocument::whereIn('id',$request->loan_ids)->update(['status'=>'selected']);
        if(isset($request->goldloan_ids))
            GoldLoanDocument::whereIn('id',$request->goldloan_ids)->update(['status'=>'selected']);
        if(isset($request->dtrf_ids))
            DtrfDocument::whereIn('id',$request->dtrf_ids)->update(['status'=>'selected']);
        if(isset($request->aof_ids))
            AccountOpeningDocument::whereIn('id',$request->aof_ids)->update(['status'=>'selected']);

        return redirect()->route('accounts.selected');
    }

    public function getBulkReview()
    {
        $allDocuments = collect();
        $loans = LoanDocument::where('branch_code', $this->user->branch_id)->where('status', 'selected')->get()
            ->map(function ($item) {
                $item->doc_type = 'loan';
                return $item;
            });
        $allDocuments = $allDocuments->merge($loans);
        $goldloans = GoldLoanDocument::where('branch_code', $this->user->branch_id)->where('status', 'selected')->get()
            ->map(function ($item) {
                $item->doc_type = 'goldloan';
                return $item;
            });
        $allDocuments = $allDocuments->merge($goldloans);
        $dtrfs = DtrfDocument::where('branch_code', $this->user->branch_id)->where('status', 'selected')->get()
            ->map(function ($item) {
                $item->doc_type = 'dtrf';
                return $item;
            });
        $allDocuments = $allDocuments->merge($dtrfs);
        $aofs = AccountOpeningDocument::where('branch_code', $this->user->branch_id)->where('status', 'selected')->get()
            ->map(function ($item) {
                $item->doc_type = 'aof';
                return $item;
            });
        $allDocuments = $allDocuments->merge($aofs);
        $couriers = Courier::pluck('name','id');
        return view('accounts.index', compact('allDocuments','couriers'));
    }
    public function addCourierDetails(Request $request)
    {
        $validated = $request->validate([
            'courier_name' => 'required|string',
            'awb_pod' => 'required|string',
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
            $dispatch->status = "Waiting Checker's Approval";
            $dispatch->save();

            if(isset($request->loan_ids))
                LoanDocument::whereIn('id',$request->loan_ids)->update(['status'=>"Waiting Checker's Approval"]);
            if(isset($request->goldloan_ids))
                GoldLoanDocument::whereIn('id',$request->goldloan_ids)->update(['status'=>"Waiting Checker's Approval"]);
            if(isset($request->dtrf_ids))
                DtrfDocument::whereIn('id',$request->dtrf_ids)->update(['status'=>"Waiting Checker's Approval"]);
            if(isset($request->aof_ids))
                AccountOpeningDocument::whereIn('id',$request->aof_ids)->update(['status'=>"Waiting Checker's Approval"]);
            
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
                    $q->where('status', "Waiting Checker's Approval");
                })
                ->when($type === 'list', function ($q){
                    $q->where('status', 'Dispatched');
                });
        };

        $records = $filter(CourierDispatch::query())->paginate(100);

        $ready_to_dispatch_count = $filter(CourierDispatch::query())->where('status',"Waiting Checker's Approval")->count();
        $dispatched_count = $filter(CourierDispatch::query())->where('status','Dispatched')->count();

        return view('accounts.dispatches', compact('ready_to_dispatch_count','dispatched_count','type','records'));
    }

    public function viewDispatches($id)
    {
        $dispatch = CourierDispatch::find($id);
        $type = 'dispatch';

        $allDocuments = collect(); 
        if (isset($dispatch->loan_ids)) {
            $loans = LoanDocument::whereIn('id', explode(',', $dispatch->loan_ids))->get()
                        ->map(function ($item) {
                            $item->doc_type = 'loan';
                            return $item;
                        });
            $allDocuments = $allDocuments->merge($loans);
        }
        if (isset($dispatch->goldloan_ids)) {
            $goldloans = GoldLoanDocument::whereIn('id', explode(',', $dispatch->goldloan_ids))->get()
                        ->map(function ($item) {
                            $item->doc_type = 'goldloan';
                            return $item;
                        });
            $allDocuments = $allDocuments->merge($goldloans);
        }
        if (isset($dispatch->dtrf_ids)) {
            $dtrfs = DtrfDocument::whereIn('id', explode(',', $dispatch->dtrf_ids))->get()
                        ->map(function ($item) {
                            $item->doc_type = 'dtrf';
                            return $item;
                        });
            $allDocuments = $allDocuments->merge($dtrfs);
        }
        if (isset($dispatch->aof_ids)) {
            $aofs = AccountOpeningDocument::whereIn('id', explode(',', $dispatch->aof_ids))->get()
                        ->map(function ($item) {
                            $item->doc_type = 'aof';
                            return $item;
                        });
            $allDocuments = $allDocuments->merge($aofs);    
        }

        return view('accounts.view', compact('allDocuments','type'));
    }

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
                $dispatch->status = "Dispatched";
                $dispatch->dispatch_no = $this->buildDispatchNumber($this->user->branch_id, '0'.$this->user->region_id, $dispatch->courier_name, $sequence,now());
                $dispatch->save();
                if($dispatch->loan_ids != null)
                LoanDocument::whereIn('id',explode(',', $dispatch->loan_ids))->update(['status'=>"Dispatched"]);
                if($dispatch->goldloan_ids != null)
                    GoldLoanDocument::whereIn('id',explode(',', $dispatch->goldloan_ids))->update(['status'=>"Dispatched"]);
                if($dispatch->dtrf_ids != null)
                    DtrfDocument::whereIn('id',explode(',', $dispatch->dtrf_ids))->update(['status'=>"Dispatched"]);
                if($dispatch->aof_ids != null)
                    AccountOpeningDocument::whereIn('id',explode(',', $dispatch->aof_ids))->update(['status'=>"Dispatched"]);

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

    public function buildDispatchNumber($branchCode, $region, $courierSlug, $sequence, $date)
    {
        $day = $date->format('d');
        $month = $date->format('m');
        $year = $date->format('y');
        $seqStr = str_pad($sequence, 3, '0', STR_PAD_LEFT);

        return "{$branchCode}{$courierSlug}{$day}{$month}{$year}{$seqStr}";
    }

    public function dispatchDetails(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'courier_received_date' => 'required|date',
            'tracked_by' => 'required|string',
            'remarks' => 'nullable|string',
            'reason_for_rejection' => 'nullable|string',
            'loan_ids' => 'nullable|array',
            'goldloan_ids' => 'nullable|array',
            'dtrf_ids' => 'nullable|array',
            'aof_ids' => 'nullable|array',
        ]);

        try {
            DB::beginTransaction();

            $updateData = [
                'courier_received_date' => $validated['courier_received_date'],
                'tracked_by' => $validated['tracked_by'],
                'remarks' => $validated['remarks'] ?? null,
                'reason_for_rejection' => $validated['reason_for_rejection'] ?? null,
                'status' => 'Dispatched'
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

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}