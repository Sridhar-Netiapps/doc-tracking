<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;

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
    public function index()
    {
        $accounts['savings'] = Account::where('type_of_account','Savings')->get();
        $accounts['current'] = Account::where('type_of_account','Current')->get();
        $accounts['loan'] = Account::where('type_of_account','Loan')->get();
        return view('accounts.accounts', compact('accounts'));
    }
}
