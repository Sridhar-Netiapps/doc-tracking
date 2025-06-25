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
        $tables = [
            LoanDocument::class,
            GoldLoanDocument::class,
            DtrfDocument::class,
            AccountOpeningDocument::class,
        ];

        foreach ($tables as $model) {
            $updated = $model::where('status',2)->update(['status' => 1]);
            $this->info($model . ": Reverted $updated documents.");
            Log::info($model . ": Reverted $updated documents.");
        }

        return 0;
    }
}
