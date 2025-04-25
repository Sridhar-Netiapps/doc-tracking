<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccountOpeningDocument;
use App\Models\DtrfDocument;
use App\Models\GoldLoanDocument;
use App\Models\LoanDocument;
use Carbon\Carbon;

class DocumentController extends Controller
{
    // Constructor for middleware
    // public function __construct()
    // {
    //     // Add the permission middleware as needed for each method
    //     // Example:
    //     // $this->middleware('permission:view-user')->only('index','show');
    //     // $this->middleware('permission:create-user')->only(['create', 'store']);
    //     // $this->middleware('permission:edit-user')->only(['edit', 'update']);
    //     // $this->middleware('permission:delete-user')->only('destroy');
    // }
    public function index(Request $request,$type)
    {
        // dd($type);
        $start_date = Carbon::now()->subWeek()->startOfWeek(); 
        $end_date = Carbon::now()->subWeek()->endOfWeek();
        if($type =='new'){
            $loan_document = LoanDocument::whereBetween('account_creation_date', [$start_date, $end_date])->paginate(100);
            $gold_loan_document = GoldLoanDocument::whereBetween('account_creation_date', [$start_date, $end_date])->paginate(100);
            $dtrf_document = DtrfDocument::whereBetween('dtr_file_date', [$start_date, $end_date])->paginate(100);
            $account_opening_document = AccountOpeningDocument::whereBetween('account_creation_date', [$start_date, $end_date])->paginate(100);
        }
        else{
            $loan_document = LoanDocument::paginate(100);
            $gold_loan_document = GoldLoanDocument::paginate(100);
            $dtrf_document = DtrfDocument::paginate(100);
            $account_opening_document = AccountOpeningDocument::paginate(100);
        }
        // if ($request->filled('unique_ref_no')) {
        //     $query->where('unique_ref_no', 'like', '%' . $request->unique_ref_no . '%');
        // }
        // if ($request->filled('region')) {
        //     $query->where('region', $request->region);
        // }
        // if ($request->filled('branch_code')) {
        //     $query->where('branch_code', $request->branch_code);
        // }
        // if ($request->filled('account_number')) {
        //     $query->where('account_number', $request->account_number);
        // }
        // if ($request->filled('customer_name')) {
        //     $query->where('customer_name', 'like', '%' . $request->customer_name . '%');
        // }
        // if ($request->filled('channel')) {
        //     $query->where('channel', $request->channel);
        // }
        // if ($request->filled('business_category')) {
        //     $query->where('business_category', $request->business_category);
        // }
        // if ($request->filled('type_of_account')) {
        //     $query->where('type_of_account', $request->type_of_account);
        // }
        // if ($request->filled('scheme')) {
        //     $query->where('scheme', $request->scheme);
        // }
        // if ($request->filled('loan_cycle')) {
        //     $query->where('loan_cycle', $request->loan_cycle);
        // }
        // if ($request->filled('from_date') && $request->filled('to_date')) {
        //     $query->whereBetween('account_creation_date', [$request->from_date, $request->to_date]);
        // }

        // $accounts['savings'] = LoanDocument::where('type_of_account','Savings')->get();
        // $accounts['current'] = LoanDocument::where('type_of_account','Current')->get();
        // $accounts['loan'] = LoanDocument::where('type_of_account','Loan')->get();
        // $query = $query->get();

        // $accounts = $query->groupBy('type_of_account');
        // dd($loan_document);
        return view('accounts.accounts', compact('loan_document','gold_loan_document','dtrf_document','account_opening_document','type'));
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
        dd($allDocuments->toArray());
        // return view('accounts.bulkReview', compact('gold_loan_document','type','dtrf_document','account_opening_document','loan_document'));

        return view('accounts.index', compact('allDocuments','type'));
    }
}
