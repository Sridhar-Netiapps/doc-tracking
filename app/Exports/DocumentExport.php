<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DocumentExport implements FromCollection, WithHeadings
{
    protected $data, $docType;

    public function __construct($data, $docType)
    {
        $this->data = $data;
        $this->docType = $docType;
    }

    public function collection()
    {
        return $this->data->map(function ($item) {
            return match ($this->docType) {
                'loan' => [
                    $item->unique_ref_no,
                    $item->branch_code,
                    $item->customer_name,
                    $item->loan_amount,
                    $item->channel,
                ],
                'gold' => [
                    $item->unique_ref_no,
                    $item->branch_code,
                    $item->loan_amount,
                    $item->barcode,
                    $item->business_category,
                ],
                'aof' => [
                    $item->unique_ref_no,
                    $item->account_number,
                    $item->customer_name,
                    $item->scheme,
                    $item->channel,
                ],
                'dtrf' => [
                    $item->barcode,
                    $item->branch_code,
                    $item->dtr_file_date,
                    $item->business_category,
                ],
            };
        });
    }

    public function headings(): array
    {
        return match ($this->docType) {
            'loan' => ['Unique Ref No', 'Branch Code', 'Customer Name', 'Loan Amount', 'Channel'],
            'gold' => ['Unique Ref No', 'Branch Code', 'Loan Amount', 'Barcode', 'Business Category'],
            'aof' => ['Unique Ref No', 'Account Number', 'Customer Name', 'Scheme', 'Channel'],
            'dtrf' => ['Barcode', 'Branch Code', 'DTR File Date', 'Business Category'],
        };
    }
}
