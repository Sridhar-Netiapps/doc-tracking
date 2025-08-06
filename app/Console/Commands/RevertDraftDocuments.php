<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LoanDocument;
use App\Models\GoldLoanDocument;
use App\Models\DtrfDocument;
use App\Models\AccountOpeningDocument;
use Illuminate\Support\Facades\Log;

class RevertDraftDocuments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'documents:revert-draft';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Revert documents from draft to pending status daily';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info("Entered In to Doc Revert");
        $tables = [
            LoanDocument::class,
            GoldLoanDocument::class,
            DtrfDocument::class,
            AccountOpeningDocument::class,
        ];

        foreach ($tables as $model) {
            Log::info("Processing to Doc Revert");
            $updated = $model::where('status',2)->get()->each(function ($doc) {
                $doc->status = 1;
                $doc->updated_by = 0;
                $doc->save();
            });
            $done = count($updated);
            $this->info($model . ": Reverted $done documents.");
            Log::info($model . ": Reverted $done documents.");
        }

        return 0;
    }
}