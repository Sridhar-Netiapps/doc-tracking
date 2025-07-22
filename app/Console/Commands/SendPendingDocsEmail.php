<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\GoldLoanDocument;
use App\Models\LoanDocument;
use App\Models\AccountOpeningDocument;
use App\Models\DtrfDocument;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use DB;

class SendPendingDocsEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'documents:sync-email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tables = [
            'loan' => LoanDocument::class,
            'goldloan' => GoldLoanDocument::class,
            'dtrf' => DtrfDocument::class,
            'aof' => AccountOpeningDocument::class,
        ];
        
        $today = '2025-07-08';//Carbon::today();

        $branchCounts = [];
    
        foreach ($tables as $label => $table) {
            $docs =$table::select('branch_code', DB::raw('COUNT(*) as count'))->where('status', 1)
                ->whereDate('created_at', $today)->groupBy('branch_code')->get();

            foreach ($docs as $row) {
                $branchCode = $row->branch_code;
                if (!isset($branchCounts[$branchCode])) {
                    $branchCounts[$branchCode] = [
                        'branch_code' => $branchCode,
                        'loan' => 0,
                        'goldloan' => 0,
                        'aof' => 0,
                        'dtrf' => 0,
                    ];
                }
                $branchCounts[$branchCode][$label] = $row->count;
            }
        }
        // dd($branchCounts);
        foreach ($branchCounts as $data) {
            $branchCode = $data['branch_code'];
            $emails = User::where('branch_id', $branchCode)
                ->whereHas('roles', function ($query) {
                    $query->whereIn('name', ['bo-maker', 'bo-checker']);
                })->pluck('email')->toArray();
            if (count($emails) === 0) {
                continue;
            }

            $html = view('emails.pending_documents', ['data' => $data])->render();
            $emails = ['sridhar@netiapps.com','ragavi@netiapps.com','suraksha@netiapps.com'];
            Mail::to($emails)->send(new \App\Mail\PendingDocsMail($html));
        }
    }
}
