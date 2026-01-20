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
   // protected $signature = 'app:export-insurance-leads';
    protected $signature = 'app:export-insurance-leads {userId} {file}';


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
        $query = InsuranceClaimDetail::with('nominee')
        ->orderBy('id', 'DESC');

        Excel::store(
            new ExportInsuranceLeads($query),
            'insurance_leads.csv',
            'local',
            ExcelExcel::CSV
        );

        $this->info('Export completed successfully');
    }
}
