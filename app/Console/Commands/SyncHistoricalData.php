<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\HrmGoldLoanDocument;
use App\Models\HrmLoanDocument;
use App\Models\HrmAccountOpeningDocument;
use App\Models\HrmDtrfDocument;
use App\Models\GoldLoanDocument;
use App\Models\LoanDocument;
use App\Models\AccountOpeningDocument;
use App\Models\DtrfDocument;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class SyncHistoricalData extends Command
{
    protected $signature = 'Historical-documents:sync';
    protected $description = 'Sync Historical documents';

    public function handle(): void
    {
        Log::info("Entered into Doc Sync");

        // $this->syncLoanType(HrmLoanDocument::class, LoanDocument::class, 'HMB');
        // $this->syncLoanType(HrmGoldLoanDocument::class, GoldLoanDocument::class, 'HGL');
        $this->syncLoanType(HrmAccountOpeningDocument::class, AccountOpeningDocument::class, 'HLD');
        // $this->syncLoanType(HrmDtrfDocument::class, DtrfDocument::class, 'HDT');

        Log::info("Completed Doc Sync");
        $this->info('All Historical documents synced successfully with unique references.');
    }

    protected function syncLoanType($sourceModel, $targetModel, string $prefix): void
    {
      
        Log::info($targetModel . ": Started syncing documents.");

        $sourceModel::chunk(100, function ($records) use ($targetModel, $prefix) {
            $grouped = $records->groupBy('branch_code');

            foreach ($grouped as $branchCode => $branchRecords) {
                $existingCount = $targetModel::where('branch_code', $branchCode)->count();
                $sequence = $existingCount;

                foreach ($branchRecords as $record) {
                    $sequence++;
                    $uniqueRefNo = $prefix .
                        str_pad($branchCode, 4, '0', STR_PAD_LEFT) .
                        str_pad($sequence, 7, '0', STR_PAD_LEFT);
                        
                    $data = $record->toArray();
                    $data['unique_ref_no'] = $uniqueRefNo;
                    $statusName = strtoupper(trim($data['status'] ?? ''));
                    $statusName = str_replace([' ', '_'], '', $statusName);

                    $statusMap = [
                        'PENDING' => 1,
                        'INDRAFT' => 2,
                        'AWAITINGCHECKERAPPROVAL' => 3,
                        'DISPATCHED' => 4,
                        'RECEIVED' => 5,
                        'REJECTED' => 6,
                        'RECEIVEDWITHQUERY' => 7,
                        'IN' => 8,
                        'OUT' => 9,
                        'PERMOUT' => 10,
                        'DESTROYED' => 11,
                    ];

                    $data['status'] = $statusMap[$statusName] ?? 1;

                    $targetModel::create($data);
                }
            }
        });

        Log::info($targetModel . ": Completed syncing documents.");
    }
}
