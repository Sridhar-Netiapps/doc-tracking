<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LoanDocument;
use App\Models\GoldLoanDocument;
use App\Models\DtrfDocument;
use App\Models\AccountOpeningDocument;
use Carbon\Carbon;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            return $next($request);
        });
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

        $filter = function ($query) {
            if ($this->user->hasRole('ro-user')) {
                $query->where('region', $this->user->region);
            }
            if ($this->user->hasRole('bo-maker') || $this->user->hasRole('bo-checker')) {
                $query->where('branch_code', $this->user->branch_id);
            }
            $query->select('status', DB::raw('count(*) as total'))->groupBy('status');

            return $query;
        };
        $loan_total = $filter(LoanDocument::query())->pluck('total', 'status')->toArray();
            // ->select('status', DB::raw('count(*) as total'))
            // ->groupBy('status')
            // ->pluck('total', 'status');

        $gold_loan_total = $filter(GoldLoanDocument::query())->pluck('total', 'status')->toArray();
            // ->select('status', DB::raw('count(*) as total'))
            // ->groupBy('status')
            // ->pluck('total', 'status');

        $dtrf_total = $filter(DtrfDocument::query())->pluck('total', 'status')->toArray();
            // ->select('status', DB::raw('count(*) as total'))
            // ->groupBy('status')
            // ->pluck('total', 'status');

        $aof_total = $filter(AccountOpeningDocument::query())->pluck('total', 'status')->toArray();
            // ->select('status', DB::raw('count(*) as total'))
            // ->groupBy('status')
            // ->get();
            
        // dd($loan_total);
        $total_doc = array_sum($loan_total) + array_sum($gold_loan_total) + array_sum($dtrf_total) + array_sum($aof_total);
        $total_pending = ($loan_total[1] ?? 0) + ($gold_loan_total[1] ?? 0) + ($dtrf_total[1] ?? 0) + ($aof_total[1] ?? 0);
        $total_selected = ($loan_total[2] ?? 0) + ($gold_loan_total[2] ?? 0) + ($dtrf_total[2] ?? 0) + ($aof_total[2] ?? 0);
        $total_dispatch = ($loan_total[3] ?? 0) + ($gold_loan_total[3] ?? 0) + ($dtrf_total[3] ?? 0) + ($aof_total[3] ?? 0);
        $total_transist = ($loan_total[4] ?? 0) + ($gold_loan_total[4] ?? 0) + ($dtrf_total[4] ?? 0) + ($aof_total[4] ?? 0);
        $total_received = ($loan_total[5] ?? 0) + ($gold_loan_total[5] ?? 0) + ($dtrf_total[5] ?? 0) + ($aof_total[5] ?? 0);
        $total_rejected = ($loan_total[6] ?? 0) + ($gold_loan_total[6] ?? 0) + ($dtrf_total[6] ?? 0) + ($aof_total[6] ?? 0);

        // $loan_total = $loan_document->total();
        // $gold_loan_total = $gold_loan_document->total();
        // $dtrf_total = $dtrf_document->total();
        // $aof_total = $account_opening_document->total();

        // Get the totals based on the selected time period (new or all)
        // $loan_total = LoanDocument::whereBetween('account_creation_date', [$start_date, $end_date])->count();
        // $gold_loan_total = GoldLoanDocument::whereBetween('account_creation_date', [$start_date, $end_date])->count();
        // $dtrf_total = DtrfDocument::whereBetween('account_creation_date', [$start_date, $end_date])->count();
        // $aof_total = AccountOpeningDocument::whereBetween('account_creation_date', [$start_date, $end_date])->count();

        // Return the view with the totals
        return view('home', compact('loan_total', 'gold_loan_total', 'dtrf_total', 'aof_total','total_doc','total_pending','total_dispatch','total_transist','total_received','total_rejected','total_selected'));
    }
}
