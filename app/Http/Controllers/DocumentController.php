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
    
    public function bulkReview(Request $request)
    {
        // dd($request->all());
        // $loan_document = $gold_loan_document = $dtrf_document = $account_opening_document = NULL;
        
        // if(isset($request->loan_ids))
        //     $loan_document = LoanDocument::whereIn('id',$request->loan_ids)->get();
        // if(isset($request->goldloan_ids))
        //     $gold_loan_document = GoldLoanDocument::whereIn('id',$request->goldloan_ids)->get();
        // if(isset($request->dtrf_ids))
        //     $dtrf_document = DtrfDocument::whereIn('id',$request->dtrf_ids)->get();
        // if(isset($request->aof_ids))
        //     $account_opening_document = AccountOpeningDocument::whereIn('id',$request->aof_ids)->get();
        // $accountIds = $request->input('account_ids', []);
        
        // if (empty($accountIds)) {
        //     return redirect()->back()->with('error', 'Please select at least one account.');
        // }
        // dd($loan_document);
        $type = 'new';
        // $accounts = LoanDocument::whereIn('id', $accountIds)->get();

        $allDocuments = collect(); 
        if (isset($request->loan_ids)) {
            $loans = LoanDocument::whereIn('id', $request->loan_ids)->get()
                        ->map(function ($item) {
                            $item->doc_type = 'loan';
                            return $item;
                        });
            $allDocuments = $allDocuments->merge($loans);
        }
        if (isset($request->goldloan_ids)) {
            $goldloans = GoldLoanDocument::whereIn('id', $request->goldloan_ids)->get()
                        ->map(function ($item) {
                            $item->doc_type = 'goldloan';
                            return $item;
                        });
            $allDocuments = $allDocuments->merge($goldloans);
        }
        if (isset($request->dtrf_ids)) {
            $dtrfs = DtrfDocument::whereIn('id', $request->dtrf_ids)->get()
                        ->map(function ($item) {
                            $item->doc_type = 'dtrf';
                            return $item;
                        });
            $allDocuments = $allDocuments->merge($dtrfs);
        }
        if (isset($request->aof_ids)) {
            $aofs = AccountOpeningDocument::whereIn('id', $request->aof_ids)->get()
                        ->map(function ($item) {
                            $item->doc_type = 'aof';
                            return $item;
                        });
            $allDocuments = $allDocuments->merge($aofs);
        }
        // dd($allDocuments->toArray());
        // return view('accounts.bulkReview', compact('gold_loan_document','type','dtrf_document','account_opening_document','loan_document'));
        return view('accounts.index', compact('allDocuments','type'));

        // if (isset($request->loan_ids)) {
        //     $loans = LoanDocument::whereIn('id', $request->loan_ids)->get()
        //         ->map(function ($item) {
        //             $item->doc_type = 'loan';
        //             return $item;
        //         });
        //     $allDocuments = $allDocuments->merge($loans);
        // }

    }
    public function addCourierDetails(Request $request)
    {
        // dd($request->all());
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
        // dd($validated);
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
        return response()->json(['success' => true]);
    }

    public function getDispatches($type)
    {
        $filter = function ($query) use ($type) {
            // if ($this->user->hasRole('ro-user')) {
            //     $query->where('region', $this->user->region);
            // }
            // if ($this->user->hasRole('bo-maker') || $this->user->hasRole('bo-checker')) {
            //     $query->where('branch_code', $this->user->branch_id);
            // }
            // return $query
            //     ->when($type === 'ready', function ($q){
            //         $q->where('status', "Waiting Checker's Approval");
            //     })
            //     ->when($type === 'list', function ($q){
            //         $q->where('status', 'Dispatched');
            //     });
        };

        // $records = $filter(CourierDispatch::query())->paginate(100);

        // dd($records);
        
        $ready_to_dispatch = CourierDispatch::where('status',"Waiting Checker's Approval")->get();
        $dispatched = CourierDispatch::where('status','Dispatched')->get();
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

        return redirect()->route('dispatches')->with('success', 'Courier Dispatched Successfully.');
        // return view('accounts.', compact('ready_to_dispatch','dispatched'));
    }
}