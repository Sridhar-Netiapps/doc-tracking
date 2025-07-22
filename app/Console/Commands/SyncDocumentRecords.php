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

class SyncDocumentRecords extends Command
{
    protected $signature = 'documents:sync';
    protected $description = 'Sync document data from staging to final tables daily';

    public function handle(): void
    {
        $this->syncLoanType(HrmLoanDocument::class, LoanDocument::class, 'MB', 'etl_date');
        $this->syncLoanType(HrmGoldLoanDocument::class, GoldLoanDocument::class, 'GL', 'etl_date');
        $this->syncLoanType(HrmAccountOpeningDocument::class, AccountOpeningDocument::class, 'LD', 'etl_date');
        $this->syncLoanType(HrmDtrfDocument::class, DtrfDocument::class, 'DT', 'etl_date');

        $this->info('All document records synced successfully with unique references.');
    }

    protected function syncLoanType($sourceModel, $targetModel, string $prefix, string $dateColumn): void
    {
        $today = now()->toDateString();
        $formattedMonthYear = now()->format('my');

        $sourceModel::whereDate($dateColumn, $today)->chunk(100, function ($records) use ($targetModel, $prefix, $formattedMonthYear) {
            $grouped = $records->groupBy('branch_code');
            foreach ($grouped as $branchCode => $branchRecords) {
                $existingCount = $targetModel::where('branch_code', $branchCode)
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count();

                $sequence = $existingCount;

                foreach ($branchRecords as $record) {
                    $sequence++;
                    $uniqueRefNo = $prefix .
                        str_pad($branchCode, 4, '0', STR_PAD_LEFT) .
                        $formattedMonthYear .
                        str_pad($sequence, 4, '0', STR_PAD_LEFT);

                    $data = $record->toArray();
                    $data['unique_ref_no'] = $uniqueRefNo;
                    $data['status'] = 1;
                    $targetModel::create($data);
                    // dd($targetModel);
                }
            }
        });
    }
}