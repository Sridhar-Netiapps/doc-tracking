<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Exports\ExportInsuranceLeads;
use App\Models\InsuranceClaimDetail;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelExcel;

class ExportInsuranceLeadsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
  protected $signature = 'insurance:export-all {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export insurance leads to CSV';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Export started...');
        $file = $this->argument('file');

        ini_set('memory_limit','-1');
        set_time_limit(0);

        $query = InsuranceClaimDetail::with('nominee')
        ->orderBy('id', 'DESC');

        Excel::store(
            new ExportInsuranceLeads($query),
            'public/exports/' . 'insurance_reports.csv',
            'local',
            ExcelExcel::CSV
        );

        $this->info('Export completed successfully');

       $sourcePath = storage_path('app/public/exports/' . 'insurance_reports.csv');
        $destinationPath = public_path('insurance_exports/' . 'insurance_reports.csv');

        if (!file_exists(public_path('insurance_exports'))) {
            mkdir(public_path('insurance_exports'), 0755, true);
        }

        rename($sourcePath, $destinationPath);

    }
}