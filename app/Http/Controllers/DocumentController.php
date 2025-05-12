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

        // // if(isset($request->loan_ids))
        //     LoanDocument::where('branch_code', $this->user->branch_id)->where('status', 'selected')->update(['status'=>'pending']);
        // // if(isset($request->goldloan_ids))
        //     GoldLoanDocument::where('branch_code', $this->user->branch_id)->where('status', 'selected')->update(['status'=>'pending']);
        // // if(isset($request->dtrf_ids))
        //     DtrfDocument::where('branch_code', $this->user->branch_id)->where('status', 'selected')->update(['status'=>'pending']);
        // // if(isset($request->aof_ids))
        //     AccountOpeningDocument::where('branch_code', $this->user->branch_id)->where('status', 'selected')->update(['status'=>'pending']);

        $filter = function ($query) use ($type, $start_date, $end_date) {
            if ($this->user->hasRole('ro-user')) {
                $query->where('region', $this->user->region);
            }
            if ($this->user->hasRole('bo-maker') || $this->user->hasRole('bo-checker')) {
                $query->where('branch_code', $this->user->branch_id);
            }
            return $query
                ->when($type === 'new', function ($q) use ($start_date, $end_date) {
                    $q->where('status','pending')->whereBetween('account_creation_date', [$start_date, $end_date]);
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
        return view('accounts.index', compact('allDocuments'));
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
            $dispatch->region = $this->user->region; 
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
                $query->where('region', $this->user->region);
            }
            if ($this->user->hasRole('bo-maker') || $this->user->hasRole('bo-checker')) {
                $query->where('branch_code', $this->user->branch_id);
            }   
            // return $query
            //     ->when($type === 'ready', function ($q){
            //         $q->where('status', "Waiting Checker's Approval");
            //     })
            //     ->when($type === 'list', function ($q){
            //         $q->where('status', 'Dispatched');
            //     });
        };

        // $records = $filter(CourierDispatch::query())->paginate(100);

        
        $ready_to_dispatch = CourierDispatch::where('status',"Waiting Checker's Approval")->get();
        $dispatched = CourierDispatch::where('status','Dispatched')->get();
        // dd($dispatched);
        return view('accounts.dispatches', compact('ready_to_dispatch','dispatched','type'));
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
        $dispatched = CourierDispatch::whereIn('id',$validated['readytodispatch_ids'])->get();
        foreach($dispatched as $dispatch){
            $dispatch->status = "Dispatched";
            $dispatch->save();
        }

        return redirect()->route('dispatches','list')->with('success', 'Courier Dispatched Successfully.');
        // return view('accounts.', compact('ready_to_dispatch','dispatched'));
    }

    public function generateDispatchNumber(string $branchCode, string $regionCode, string $courierName): string
    {
        $today = Carbon::today();
        $datePart = $today->format('dm y'); // e.g., 120524
        $slug = strtoupper(Str::slug($courierName, ''));

        // Fetch today's count for the same branch and courier
        $count = DB::table('courier_dispatches')
            ->where('branch_code', $branchCode)
            ->where('courier_slug', $slug)
            ->whereDate('created_at', $today)
            ->count();

        $sequence = str_pad($count + 1, 3, '0', STR_PAD_LEFT);

        return "{$branchCode}{$regionCode}{$slug}{$datePart}{$sequence}";
    }
}