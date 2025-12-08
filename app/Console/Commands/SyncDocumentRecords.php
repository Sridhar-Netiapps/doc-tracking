<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\HrmGoldLoanDocument;
use App\Models\HrmLoanDocument;
use App\Models\HrmAccountOpeningDocument;
use App\Models\HrmDtrfDocument;
use App\Models\GoldLoanDocument;
use App\Models\LoanDocument;
use App\Models\AccountOpeningDocument;
use App\Models\DtrfDocument;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SyncDocumentRecords extends Command
{
    protected $signature = 'documents:sync';
    protected $description = 'Sync document data from staging to final tables daily';

    public function handle(): void
    {
        Log::info("Entered In to Doc Sync");
        $result['loan'] = $this->syncLoanType(HrmLoanDocument::class, LoanDocument::class, 'MB', 'added_at');
        $result['goldloan'] = $this->syncLoanType(HrmGoldLoanDocument::class, GoldLoanDocument::class, 'GL', 'added_at');
        $result['aof'] = $this->syncLoanType(HrmAccountOpeningDocument::class, AccountOpeningDocument::class, 'LD', 'added_at');
        $result['dtrf'] = $this->syncLoanType(HrmDtrfDocument::class, DtrfDocument::class, 'DT', 'added_at');
        Log::info("Completed Doc Sync");
        $this->sendEmail($result);
        $this->info('All document records synced successfully with unique references.');
    }

    protected function syncLoanType($sourceModel, $targetModel, string $prefix, string $dateColumn)
    {
        $today = now()->toDateString();
        $formattedMonthYear = now()->format('my');
        Log::info($targetModel . ": Started documents.");

        $sourceModel::whereDate($dateColumn, $today)->chunk(100, function ($records) use ($targetModel, $prefix, $formattedMonthYear) {
            $grouped = $records->groupBy('branch_code');
            foreach ($grouped as $branchCode => $branchRecords) {
                foreach ($branchRecords as $record) {
                    $uniqueRefNo = $targetModel::where('branch_code', $branchCode)->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year)->max('unique_ref_no');

                    if($uniqueRefNo == null)
                    {
                        $uniqueRefNo = $prefix . str_pad($branchCode, 4, '0', STR_PAD_LEFT) . $formattedMonthYear . str_pad(1, 4, '0', STR_PAD_LEFT);
                    }
                    else{
                        $uniqueRefNo++;
                    }
                    
                    $data = $record->toArray();
                    $data['unique_ref_no'] = $uniqueRefNo;
                    $data['status'] = 1;
                    $targetModel::create($data);
                }
            }
        });
        Log::info($targetModel . ": Done documents.");
        return $targetModel::whereDate('created_at',$today)->count();
    }
    protected function sendEmail($data): void
    {
        $html = view('emails.doc_sync_complete', ['data' => $data])->render();
        $subject = "Document Tracking – Document Syncing Completed";
        $emails = ['sridhar@netiapps.com','ragavi@netiapps.com','suraksha@netiapps.com'];
        Mail::to($emails)->send(new \App\Mail\SyncMail($html, $subject));
    }
}