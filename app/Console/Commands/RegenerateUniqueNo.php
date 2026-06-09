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
    // protected $signature = 'sync:regenerate';
    // protected $description = 'Regenerate unique_ref_no (only those starting with H) safely and efficiently.';

    /** Prefix mapping for document types */
    // private const PREFIX_MAP = [
    //     'loan'     => 'MB',
    //     'goldloan' => 'GL',
    //     'aof'      => 'LD',
    //     'dtrf'     => 'DT',
    // ];

    // /** Model mapping for each document type */
    // private const TABLE_MAP = [
    //     'loan'     => LoanDocument::class,
    //     'goldloan' => GoldLoanDocument::class,
    //     'aof'      => AccountOpeningDocument::class,
    //     'dtrf'     => DtrfDocument::class,
    // ];

    // public function handle(): void
    // {
    //     $this->info('Starting regeneration of unique_ref_no (only H-prefixed)...');

    //     foreach (self::TABLE_MAP as $docType => $model) {
    //         $prefix = self::PREFIX_MAP[$docType] ?? strtoupper($docType);
    //         $this->processTable($model, $prefix, $docType);
    //     }

    //     $this->info('All reference numbers regenerated successfully!');
    // }

   
    // private function processTable(string $modelClass, string $prefix, string $docType): void
    // {
    //     $this->line("\n Processing {$docType} documents...");

    //     // Collect distinct branches that have H-prefixed ref numbers
    //     $branches = $modelClass::query()
    //         ->where('unique_ref_no', 'like', 'H%')
    //         ->whereNotNull('branch_code')
    //         ->distinct()
    //         ->pluck('branch_code');

    //     if ($branches->isEmpty()) {
    //         $this->warn("    No H-prefixed records found in {$docType}.");
    //         return;
    //     }

    //     foreach ($branches as $branchCode) {
    //         $this->updateBranchRecords($modelClass, $prefix, $branchCode);
    //     }
    // }

    // private function updateBranchRecords(string $modelClass, string $prefix, string $branchCode): void
    // {
    //     DB::beginTransaction();
    //     try {
    //         $documents = $modelClass::query()
    //             ->where('unique_ref_no', 'like', 'H%')
    //             ->where('branch_code', $branchCode)
    //             ->orderBy('created_at')
    //             ->get(['id', 'unique_ref_no']);

    //         if ($documents->isEmpty()) {
    //             DB::rollBack();
    //             return;
    //         }

    //         $updates = [];
    //         foreach ($documents as $index => $document) {
    //             $newRef = sprintf(
    //                 'H%s%s%07d',
    //                 $prefix,
    //                 $branchCode,
    //                 $index + 1
    //             );
    //             $updates[$document->id] = $newRef;
    //         }

    //         foreach ($updates as $id => $newRef) {
    //             $modelClass::where('id', $id)->update(['unique_ref_no' => $newRef]);
    //         }

    //         DB::commit();
    //         $this->info("   Updated " . count($updates) . " records for branch {$branchCode}.");
    //     } catch (\Throwable $e) {
    //         DB::rollBack();
    //         $this->error("   Error in branch {$branchCode}: {$e->getMessage()}");
    //     }
    // }

    protected $signature = 'documents:fix-duplicates{--dry-run : Show every change without writing anything}';
 
    protected $description = 'Find duplicate unique_ref_no values and renumber the extras per branch/month';
 
    /**
     * Tables to clean. dtrf_documents has 0 duplicates, so it is left out.
     */
    protected array $tables = [
        'account_opening_documents',
        'loan_documents',
        'gold_loan_documents',
    ];
 
    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
 
        if ($dryRun) {
            $this->warn('DRY RUN — no changes will be written to any table.');
        } else {
            if (! $this->confirm('This will renumber duplicate refs in production tables. Did you take a backup?')) {
                $this->error('Aborted. Take a backup first.');
                return self::FAILURE;
            }
        }
 
        foreach ($this->tables as $table) {
            $this->processTable($table, $dryRun);
        }
 
        $this->line('');
        $this->info('Done.');
 
        return self::SUCCESS;
    }
 
    protected function processTable(string $table, bool $dryRun): void
    {
        $this->line('');
        $this->info("=== {$table} ===");
 
        // 1) Find duplicated ref values.
        $dupRefs = DB::table($table)
            ->select('unique_ref_no')
            ->whereNotNull('unique_ref_no')
            ->groupBy('unique_ref_no')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('unique_ref_no');
 
        if ($dupRefs->isEmpty()) {
            $this->line('No duplicates. Nothing to do.');
            return;
        }
 
        $this->line("Duplicate ref values found: {$dupRefs->count()}");
 
        // 2) For each duplicate group keep the earliest row; collect the rest,
        //    grouped by base (prefix+branch+mmyy) so refs in the same
        //    branch/month share one continuing sequence.
        $toRenumber = [];   // base => [rows...]
        $malformed  = [];   // refs that don't match the 14-char format
        $keptCount  = 0;
        $moveCount  = 0;
 
        foreach ($dupRefs as $ref) {
            // Guard: only handle the expected fixed-width format.
            if (strlen($ref) !== 14) {
                $malformed[] = $ref;
                continue;
            }
 
            $rows = DB::table($table)
                ->where('unique_ref_no', $ref)
                ->orderBy('created_at')
                ->orderBy('id')
                ->get(['id', 'branch_code', 'unique_ref_no', 'created_at']);
 
            $rows->shift();           // keep the earliest row untouched
            $keptCount++;
 
            foreach ($rows as $row) {
                $base = substr($row->unique_ref_no, 0, 10);
                $toRenumber[$base][] = $row;
                $moveCount++;
            }
        }
 
        $this->line("Rows kept (one per group): {$keptCount}");
        $this->line("Rows to renumber:          {$moveCount}");
 
        if (! empty($malformed)) {
            $this->warn('Skipped ' . count($malformed) . ' duplicate ref(s) that are NOT 14 chars '
                . '(need manual review): ' . implode(', ', $malformed));
        }
 
        if (empty($toRenumber)) {
            $this->line('Nothing to renumber after format checks.');
            return;
        }
 
        // 3) Apply (or preview) the renumbering, base by base.
        $apply = function () use ($table, $toRenumber) {
            foreach ($toRenumber as $base => $rows) {
                // Assign the earliest extras the lowest new numbers.
                usort($rows, function ($a, $b) {
                    return [$a->created_at, $a->id] <=> [$b->created_at, $b->id];
                });
 
                // Current max sequence for this branch+month, across ALL rows.
                $maxSeq = (int) DB::table($table)
                    ->where('unique_ref_no', 'like', $base . '%')
                    ->lockForUpdate()
                    ->selectRaw('MAX(CAST(SUBSTRING(unique_ref_no, 11, 4) AS UNSIGNED)) AS m')
                    ->value('m');
 
                $next = $maxSeq + 1;
 
                foreach ($rows as $row) {
                    $newRef = $base . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
 
                    $this->line(sprintf(
                        '  branch %-6s  id %-8s  %s  ->  %s',
                        $row->branch_code,
                        $row->id,
                        $row->unique_ref_no,
                        $newRef
                    ));
 
                    if (! $this->option('dry-run')) {
                        DB::table($table)
                            ->where('id', $row->id)
                            ->update([
                                'unique_ref_no' => $newRef,
                                'updated_at'    => DB::raw('updated_at'), // freeze timestamp
                            ]);
                    }
 
                    $next++;
                }
            }
        };
 
        if ($dryRun) {
            $apply();
            $this->warn("DRY RUN — nothing written for {$table}.");
            return;
        }
 
        DB::transaction($apply);
        $this->info("Applied changes to {$table}.");
 
        // 4) Verify no duplicates remain.
        $remaining = DB::table($table)
            ->select('unique_ref_no')
            ->groupBy('unique_ref_no')
            ->havingRaw('COUNT(*) > 1')
            ->get()
            ->count();
 
        if ($remaining === 0) {
            $this->info("Verified: no duplicates remain in {$table}.");
        } else {
            $this->error("WARNING: {$remaining} duplicate ref value(s) still present in {$table}.");
        }
    }

}
