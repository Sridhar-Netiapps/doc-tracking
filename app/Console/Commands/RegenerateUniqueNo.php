<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\LoanDocument;
use App\Models\GoldLoanDocument;
use App\Models\AccountOpeningDocument;
use App\Models\DtrfDocument;

class RegenerateUniqueNo extends Command
{
    protected $signature = 'sync:regenerate';
    protected $description = 'Regenerate unique_ref_no (only those starting with H) safely and efficiently.';

    /** Prefix mapping for document types */
    private const PREFIX_MAP = [
        'loan'     => 'MB',
        'goldloan' => 'GL',
        'aof'      => 'LD',
        'dtrf'     => 'DT',
    ];

    /** Model mapping for each document type */
    private const TABLE_MAP = [
        'loan'     => LoanDocument::class,
        'goldloan' => GoldLoanDocument::class,
        'aof'      => AccountOpeningDocument::class,
        'dtrf'     => DtrfDocument::class,
    ];

    public function handle(): void
    {
        $this->info('Starting regeneration of unique_ref_no (only H-prefixed)...');

        foreach (self::TABLE_MAP as $docType => $model) {
            $prefix = self::PREFIX_MAP[$docType] ?? strtoupper($docType);
            $this->processTable($model, $prefix, $docType);
        }

        $this->info('All reference numbers regenerated successfully!');
    }

   
    private function processTable(string $modelClass, string $prefix, string $docType): void
    {
        $this->line("\n Processing {$docType} documents...");

        // Collect distinct branches that have H-prefixed ref numbers
        $branches = $modelClass::query()
            ->where('unique_ref_no', 'like', 'H%')
            ->whereNotNull('branch_code')
            ->distinct()
            ->pluck('branch_code');

        if ($branches->isEmpty()) {
            $this->warn("    No H-prefixed records found in {$docType}.");
            return;
        }

        foreach ($branches as $branchCode) {
            $this->updateBranchRecords($modelClass, $prefix, $branchCode);
        }
    }

    private function updateBranchRecords(string $modelClass, string $prefix, string $branchCode): void
    {
        DB::beginTransaction();
        try {
            $documents = $modelClass::query()
                ->where('unique_ref_no', 'like', 'H%')
                ->where('branch_code', $branchCode)
                ->orderBy('created_at')
                ->get(['id', 'unique_ref_no']);

            if ($documents->isEmpty()) {
                DB::rollBack();
                return;
            }

            $updates = [];
            foreach ($documents as $index => $document) {
                $newRef = sprintf(
                    'H%s%s%07d',
                    $prefix,
                    $branchCode,
                    $index + 1
                );
                $updates[$document->id] = $newRef;
            }

            foreach ($updates as $id => $newRef) {
                $modelClass::where('id', $id)->update(['unique_ref_no' => $newRef]);
            }

            DB::commit();
            $this->info("   Updated " . count($updates) . " records for branch {$branchCode}.");
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error("   Error in branch {$branchCode}: {$e->getMessage()}");
        }
    }
}
