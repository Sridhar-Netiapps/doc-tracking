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
        $today = '2025-10-23'; // Hardcoded for testing purposes    
        $formattedMonthYear = now()->format('my');
        $currentMonth = now()->month;
        $currentYear = now()->year;
        
        Log::info($targetModel . ": Started documents.");

        // Chunk size optimized to 200 for a solid balance of memory vs query volume
        $sourceModel::whereDate($dateColumn, $today)->chunk(200, function ($records) use ($targetModel, $prefix, $formattedMonthYear, $currentMonth, $currentYear) {
            
            // 1. Pre-fetch the highest current reference number for ALL branches involved in this chunk
            $branchCodes = $records->pluck('branch_code')->unique()->toArray();
            
            $latestRefs = $targetModel::whereIn('branch_code', $branchCodes)
                ->whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->groupBy('branch_code')
                ->select('branch_code', \DB::raw('MAX(unique_ref_no) as max_ref'))
                ->pluck('max_ref', 'branch_code')
                ->toArray();

            $bulkInsertData = [];

            // 2. Process records in memory groups
            $grouped = $records->groupBy('branch_code');
            foreach ($grouped as $branchCode => $branchRecords) {
                
                // Determine the starting pointer for this specific branch
                if (!isset($latestRefs[$branchCode]) || $latestRefs[$branchCode] == null) {
                    $currentMaxRef = null;
                } else {
                    $currentMaxRef = $latestRefs[$branchCode];
                }

                foreach ($branchRecords as $record) {
                    if ($currentMaxRef === null) {
                        $uniqueRefNo = $prefix . str_pad($branchCode, 4, '0', STR_PAD_LEFT) . $formattedMonthYear . str_pad(1, 4, '0', STR_PAD_LEFT);
                        $currentMaxRef = $uniqueRefNo; // seed the tracking variable
                    } else {
                        // String increment trick: 'GL000106260001'++ automatically becomes 'GL000106260002'
                        $currentMaxRef++; 
                        $uniqueRefNo = $currentMaxRef;
                    }

                    $data = $record->toArray();
                    
                    // Security Cleanup: Remove primary key tracking to avoid constraint violation on target tables
                    unset($data['id']);
                    unset($data['added_at']);
                    
                    $data['unique_ref_no'] = $uniqueRefNo;
                    $data['status'] = 1;
                    
                    // Add timestamps manually since bulk insert bypasses Eloquent lifecycle hooks
                    $data['created_at'] = now();
                    $data['updated_at'] = now();

                    $bulkInsertData[] = $data;
                }
            }

            // 3. High Performance Database Write (Executes 1 query instead of hundreds)
            if (!empty($bulkInsertData)) {
                $targetModel::insert($bulkInsertData);
            }
        });

        Log::info($targetModel . ": Done documents.");
        return $targetModel::whereDate('created_at', $today)->count();
    }
    protected function sendEmail($data): void
    {
        $html = view('emails.doc_sync_complete', ['data' => $data])->render();
        $subject = "Document Tracking – Document Syncing Completed";
        $emails = ['sridhar@netiapps.com','ragavi@netiapps.com','suraksha@netiapps.com'];
        // $emails =  ['shekhar.poojary@ujjivan.com','maharajan.d@ujjivan.com','anand.m@ujjivan.com','bharathi.k276@ujjivan.com','yasotha.a@ujjivan.com','vidyasagar@ujjivan.com','krishnakumar.marapalli@ujjivan.com','d.robin@ujjivan.com','b.nithin@ujjivan.com','vigneshwari.r633@ujjivan.com'];
        Mail::to($emails)->send(new \App\Mail\SyncMail($html, $subject));
    }
}