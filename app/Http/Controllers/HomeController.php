<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LoanDocument;
use App\Models\GoldLoanDocument;
use App\Models\DtrfDocument;
use App\Models\AccountOpeningDocument;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        // Set date range for the previous week
        $start_date = Carbon::now()->subWeek()->startOfWeek();
        $end_date = Carbon::now()->subWeek()->endOfWeek();

        // Get the totals based on the selected time period (new or all)
        $loan_total = LoanDocument::whereBetween('account_creation_date', [$start_date, $end_date])->count();
        $gold_loan_total = GoldLoanDocument::whereBetween('account_creation_date', [$start_date, $end_date])->count();
        $dtrf_total = DtrfDocument::whereBetween('account_creation_date', [$start_date, $end_date])->count();
        $aof_total = AccountOpeningDocument::whereBetween('account_creation_date', [$start_date, $end_date])->count();

        // Return the view with the totals
        return view('home', compact('loan_total', 'gold_loan_total', 'dtrf_total', 'aof_total'));
    }
}
