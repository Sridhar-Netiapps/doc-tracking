<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ExportInsuranceLeads implements
    FromQuery,
    WithHeadings,
    WithMapping,
    WithChunkReading
    
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    /**
     * Fetch data in chunks (NOT all at once)
     */
    public function query()
    {
        return $this->query->with('nominee');
    }

    /**
     * Map each row (called per row, per chunk)
     */
    public function map($value): array
    {
        return [
            optional($value->created_at)->format('d-m-Y'),
            optional($value->updated_at)->format('d-m-Y'),
            $value->id,
            $value->utrn,
            $value->region,
            $value->branch,
            $value->partner,
            $value->product,
            $value->mp_no,
            $value->policy_number,
            optional($value->policy_covered_date)->format('d-m-Y'),
            optional($value->policy_expiry_date)->format('d-m-Y'),
            $value->cust_id,
            $value->actual_id,
            $value->deceased_name,
            optional($value->dob)->format('d-m-Y'),
            optional($value->date_of_death)->format('d-m-Y'),
            $value->gender,
            $value->age,
            $value->deceased,
            optional($value->intimation_date)->format('d-m-Y'),
            $value->place_of_death,
            $value->cause_of_death,
            '="'.$value->load_acc_id.'"',
            $value->loan_tenure,
            $value->claim_amount,
            $value->nominee->nominee_name_bank ?? '',
            $value->nominee->bank_name ?? '',
            '="'.($value->nominee->acc_number ?? '').'"',
            $value->nominee->ifsc ?? '',
            $value->nominee->branch_name ?? '',
        ];
    }

    /**
     * Chunk size (important)
     */
    public function chunkSize(): int
    {
        return 1000; // safe & fast
    }

    public function headings(): array
    {
        return [
            'Creation Date',
            'Last Modified Date',
            'Reference ID',
            'UTRN',
            'Region',
            'Branch',
            'Partner',
            'Product',
            'Member Code',
            'Policy Number',
            'Policy Covered Date',
            'Policy Expiry Date',
            'Customer ID',
            'Actual ID',
            'Deceased Name',
            'DOB',
            'Date of Death',
            'Gender',
            'Age',
            'Deceased',
            'Intimation Date',
            'Place of Death',
            'Cause of Death',
            'Loan Account ID',
            'Loan Tenure',
            'Claim Amount',
            'Nominee Name',
            'Bank Name',
            'Account Number',
            'IFSC',
            'Branch Name',
        ];
    }
}

