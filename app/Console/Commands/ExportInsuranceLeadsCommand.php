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
    //protected $signature = 'app:export-insurance-leads';
   // protected $signature = 'app:export-insurance-leads {userId} {file}';
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
        $file = $this->argument('file')."_".date('d_M_Y_H_i');
        $query = InsuranceClaimDetail::with('nominee')
        ->orderBy('id', 'DESC');

        Excel::store(
            new ExportInsuranceLeads($query),
            'public/exports/' .'insurance_report.csv',
            'public',
            ExcelExcel::CSV
        );

        // Move to public folder
        $sourcePath = storage_path('app/public/exports/' . 'insurance_report.csv');
        $destinationPath = public_path('insurance_exports/' . 'insurance_report.csv');

        if (!file_exists(public_path('insurance_exports'))) {
            mkdir(public_path('insurance_exports'), 0755, true);
        }

        rename($sourcePath, $destinationPath);

        $this->info('Export completed successfully');
    }
}
