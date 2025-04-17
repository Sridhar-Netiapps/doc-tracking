<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use Carbon\Carbon;

class AccountController extends Controller
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
        $query = Account::whereIn('type_of_account', ['Savings', 'Current', 'Loan']);
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
        if($type =='new'){
            $query->whereBetween('account_creation_date', [$start_date, $end_date]);
        }

        // $accounts['savings'] = Account::where('type_of_account','Savings')->get();
        // $accounts['current'] = Account::where('type_of_account','Current')->get();
        // $accounts['loan'] = Account::where('type_of_account','Loan')->get();
        $query = $query->get();

        $accounts = $query->groupBy('type_of_account');
        // dd($accounts);
        return view('accounts.accounts', compact('accounts','type'));

    }
        public function bulkReview(Request $request)
    {
        $accountIds = $request->input('account_ids', []);
        
        if (empty($accountIds)) {
            return redirect()->back()->with('error', 'Please select at least one account.');
        }

        $accounts = Account::whereIn('id', $accountIds)->get();

        return view('accounts.bulk_review', compact('accounts'))->with('type', 'Savings');

    }


}
