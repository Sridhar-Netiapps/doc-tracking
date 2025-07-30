<?php

namespace App\Exports;

use App\Models\GoldLoanDocument;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class GoldLoanDocumentExport implements FromCollection, WithHeadings, WithMapping
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        // dd($this->data);
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'Unique Number',
            'Region',
            'Branch Code',
            'Branch Name',
            'CIF ID',
            'A/C No',
            'Customer Name',
            'Creation Date',
            'Channel',
            'Loan Amount',
            'Business Category',
            'Barcode',
            'AWB/POD',
            'Courier name',
            'Dispatch Date',
            'Dispatched By (User ID)',
            'Courier Received date @ Mail Room',
            'Tracked by (User ID)',
            'Remarks',
            'Reason for Rejection',
            'Lot No',
            'Document Category',
            'Work Order No',
            'Vendor Name',
            'Date of  Vendor Movement',
            'File Barcode',
            'Box Barcode',
            'Date of addition to Vendor Data',
            'Status',
        ];
    }

    public function map($doc): array
    {
        return [
            $doc->unique_ref_no,
            $doc->region,
            $doc->branch_code,
            $doc->branch_name,
            $doc->cif_id,
            $doc->account_number,
            $doc->customer_name,
            $doc->account_creation_date,
            $doc->channel,
            $doc->loan_amount,
            $doc->business_category,
            $doc->barcode,
            $doc->dispatch->awb_pod,
            $doc->dispatch->courierName->name,
            $doc->dispatch->dispatch_date,
            $doc->dispatch->dispatcher->first_name,
            $doc->dispatch->courier_received_date,
            $doc->dispatch->status == '12' ? $doc->modifier->first_name : '-' ,
            $doc->dispatch->statusName->name,
            $doc->dispatch->comments == null ? $doc->dispatch->comments : '-',
            $doc->lot_no,
            $doc->category_of_document,
            $doc->work_order_no,
            $doc->vendor_name,
            $doc->vendor_movement_date,
            $doc->file_barcode,
            $doc->box_barcode,
            $doc->date_added_to_vendor,
            $doc->statusName->name,
        ];
    }
}