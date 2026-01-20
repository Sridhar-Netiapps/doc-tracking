<?php

namespace App\Jobs;

use App\Exports\ExportInsuranceLeads;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ExportInsuranceLeadsJob
{
    use Queueable;
    
    protected $query;
    protected $additionalFields;
    protected $filename;


    /**
     * Create a new job instance.
     */
    public function __construct($query, $additionalFields, $filename)
    {
        $this->query = $query;
        $this->additionalFields = $additionalFields;
        $this->filename = $filename;
    }

    public function handle()
    {
        Excel::store(
            new ExportInsuranceLeads($this->query, $this->additionalFields),
            'exports/' . $this->filename,
            'public',
            \Maatwebsite\Excel\Excel::CSV
        );

        
    }
}
