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
            if ($this->user->hasRole('ro-officer') || $this->user->hasRole('ro-supervisor')) {
                $query->where('region', $this->user->region);
            }
            if ($this->user->hasRole('bo-maker') || $this->user->hasRole('bo-checker')) {
                $query->where('branch_code', $this->user->branch_id);
            }
            $query->select('status', DB::raw('count(*) as total'))->groupBy('status');

            return $query;
        };
        $loan_total = $filter(LoanDocument::query())->pluck('total', 'status')->toArray();
        $gold_loan_total = $filter(GoldLoanDocument::query())->pluck('total', 'status')->toArray();
        $dtrf_total = $filter(DtrfDocument::query())->pluck('total', 'status')->toArray();
        $aof_total = $filter(AccountOpeningDocument::query())->pluck('total', 'status')->toArray();

               // TODAY
        $dailyFilter = function ($query) {
            $query->whereDate('updated_at', Carbon::today());

            if ($this->user->hasRole('ro-officer') || $this->user->hasRole('ro-supervisor')) {
                $query->where('region', $this->user->region);
            }
            if ($this->user->hasRole('bo-maker') || $this->user->hasRole('bo-checker')) {
                $query->where('branch_code', $this->user->branch_id);
            }
            $query->select('status', DB::raw('count(*) as total'))->groupBy('status');
            return $query;
        };
            
        $loan_today = $dailyFilter(LoanDocument::query())->pluck('total', 'status')->toArray();
        $gold_loan_today = $dailyFilter(GoldLoanDocument::query())->pluck('total', 'status')->toArray();
        $dtrf_today = $dailyFilter(DtrfDocument::query())->pluck('total', 'status')->toArray();
        $aof_today = $dailyFilter(AccountOpeningDocument::query())->pluck('total', 'status')->toArray();

        $total_doc_today = array_sum($loan_today) + array_sum($gold_loan_today) + array_sum($dtrf_today) + array_sum($aof_today);
        $total_selected_today = ($loan_today[2] ?? 0) + ($gold_loan_today[2] ?? 0) + ($dtrf_today[2] ?? 0) + ($aof_today[2] ?? 0);
        $total_pending_today = ($loan_today[1] ?? 0) + ($gold_loan_today[1] ?? 0) + ($dtrf_today[1] ?? 0) + ($aof_today[1] ?? 0) + $total_selected_today;
        $total_dispatch_today = ($loan_today[3] ?? 0) + ($gold_loan_today[3] ?? 0) + ($dtrf_today[3] ?? 0) + ($aof_today[3] ?? 0);
        $total_transist_today = ($loan_today[4] ?? 0) + ($gold_loan_today[4] ?? 0) + ($dtrf_today[4] ?? 0) + ($aof_today[4] ?? 0);
        $total_received_query_today = ($loan_today[7] ?? 0) + ($gold_loan_today[7] ?? 0) + ($dtrf_today[7] ?? 0) + ($aof_today[7] ?? 0);
        $total_dispatched_today = 
            ($loan_today[8] ?? 0) + ($gold_loan_today[8] ?? 0) + ($dtrf_today[8] ?? 0) + ($aof_today[8] ?? 0) +
            ($loan_today[9] ?? 0) + ($gold_loan_today[9] ?? 0) + ($dtrf_today[9] ?? 0) + ($aof_today[9] ?? 0) +
            ($loan_today[10] ?? 0) + ($gold_loan_today[10] ?? 0) + ($dtrf_today[10] ?? 0) + ($aof_today[10] ?? 0) +
            ($loan_today[11] ?? 0) + ($gold_loan_today[11] ?? 0) + ($dtrf_today[11] ?? 0) + ($aof_today[11] ?? 0);
        $total_received_today = ($loan_today[5] ?? 0) + ($gold_loan_today[5] ?? 0) + ($dtrf_today[5] ?? 0) + ($aof_today[5] ?? 0) + $total_received_query_today + $total_dispatched_today ;
        $total_rejected_today = ($loan_today[6] ?? 0) + ($gold_loan_today[6] ?? 0) + ($dtrf_today[6] ?? 0) + ($aof_today[6] ?? 0);

    
        $total_doc = array_sum($loan_total) + array_sum($gold_loan_total) + array_sum($dtrf_total) + array_sum($aof_total);
        $total_selected = ($loan_total[2] ?? 0) + ($gold_loan_total[2] ?? 0) + ($dtrf_total[2] ?? 0) + ($aof_total[2] ?? 0);
        $total_pending = ($loan_total[1] ?? 0) + ($gold_loan_total[1] ?? 0) + ($dtrf_total[1] ?? 0) + ($aof_total[1] ?? 0) + $total_selected;
        $total_dispatch = ($loan_total[3] ?? 0) + ($gold_loan_total[3] ?? 0) + ($dtrf_total[3] ?? 0) + ($aof_total[3] ?? 0);
        $total_transist = ($loan_total[4] ?? 0) + ($gold_loan_total[4] ?? 0) + ($dtrf_total[4] ?? 0) + ($aof_total[4] ?? 0);
        $total_received_query = ($loan_total[7] ?? 0) + ($gold_loan_total[7] ?? 0) + ($dtrf_total[7] ?? 0) + ($aof_total[7] ?? 0);
        $total_dispatched = 
            ($loan_total[8] ?? 0) + ($gold_loan_total[8] ?? 0) + ($dtrf_total[8] ?? 0) + ($aof_total[8] ?? 0) +
            ($loan_total[9] ?? 0) + ($gold_loan_total[9] ?? 0) + ($dtrf_total[9] ?? 0) + ($aof_total[9] ?? 0) +
            ($loan_total[10] ?? 0) + ($gold_loan_total[10] ?? 0) + ($dtrf_total[10] ?? 0) + ($aof_total[10] ?? 0) +
            ($loan_total[11] ?? 0) + ($gold_loan_total[11] ?? 0) + ($dtrf_total[11] ?? 0) + ($aof_total[11] ?? 0);
        $total_received = ($loan_total[5] ?? 0) + ($gold_loan_total[5] ?? 0) + ($dtrf_total[5] ?? 0) + ($aof_total[5] ?? 0) + $total_received_query + $total_dispatched ;
        $total_rejected = ($loan_total[6] ?? 0) + ($gold_loan_total[6] ?? 0) + ($dtrf_total[6] ?? 0) + ($aof_total[6] ?? 0);

        $type = 'home';

        return view('home', compact('loan_total', 'gold_loan_total', 'dtrf_total', 'aof_total','total_doc','total_pending',
        'total_dispatch','total_transist','total_received','total_rejected','total_selected', 'total_received_query',  
         'total_doc_today', 'loan_today', 'gold_loan_today', 'dtrf_today', 'aof_today','total_pending_today',
         'total_dispatch_today','total_transist_today','total_received_today','total_rejected_today','total_selected_today', 'total_received_query_today', 'type'));
    }
}
