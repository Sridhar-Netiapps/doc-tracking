<?php

namespace App\Exports;

use App\Models\AccountOpeningDocument;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AccountOpeningDocumentExport implements FromCollection, WithHeadings, WithMapping
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
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
            'Account Number',
            'Customer Name',
            'Creation Date',
            'Scheme',
            'Channel',
            'PGK No',
            'Account Opening Type',
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
            $doc->account_creation_date != null ? date('d-m-Y', strtotime($doc->account_creation_date)) : '-',
            $doc->scheme,
            $doc->channel,
            $doc->pgk_no,
            $doc->type_of_account_opening,
            $doc->business_category,
            $doc->barcode,
            $doc->status >= '4' ? $doc->dispatch->awb_pod : '-',
            $doc->status >= '4' ? $doc->dispatch->courierName->name : '-',
            $doc->status >= '4' ? ($doc->dispatch->dispatch_date != null ? date('d-m-Y', strtotime($doc->dispatch->dispatch_date)) : '-'): '-',
            $doc->status >= '4' ? ($doc->dispatch->status >= '4' ? $doc->dispatch->dispatcher->first_name . ' (' . $doc->dispatch->dispatcher->employee_id . ')' : '-'): '-',
            $doc->status >= '4' ? ($doc->getReceivedDate != null ? date('d-m-Y', strtotime($doc->getReceivedDate->created_at)) : '-') : '-', 
            $doc->status >= '4' ? ($doc->dispatch->status == '12' ? $doc->modifier->first_name : '-'): '-',
            $doc->status >= '4' ? $doc->dispatch->statusName->name : '-',
            $doc->status >= '4' ? $doc->dispatch->comments : '-',
            $doc->lot_no,
            $doc->category_of_document,
            $doc->work_order_no,
            $doc->vendor_name,
            $doc->vendor_movement_date != null ? date('d-m-Y', strtotime($doc->vendor_movement_date)) : '-',
            $doc->file_barcode,
            $doc->box_barcode,
            $doc->date_added_to_vendor != null ? date('d-m-Y', strtotime($doc->date_added_to_vendor)) : '-',
            $doc->statusName->name,
        ];
    }
}