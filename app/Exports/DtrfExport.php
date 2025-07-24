<?php

namespace App\Exports;

use App\Models\DtrfDocument;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DtrfExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filterCallback;

    public function __construct($filterCallback)
    {
        $this->filterCallback = $filterCallback;
    }

    public function collection()
    {
        return DtrfDocument::where(function ($query) {
            ($this->filterCallback)($query, 'dtrf_documents');
        })->get();
    }

    public function headings(): array
    {
        return [
            'Unique Number',
            'Region',
            'Branch Code',
            'Branch Name',
            'DTR File Date',
            'Barcode',
            'Business Category',
            'AWB/POD',
            'Courier name',
            'Dispatch Date',
            'Dispatched By (User ID)',
            'Courier Received date @ Mail Room',
            'Tracked by (User ID)',
            'Remarks (Received / Rejected)',
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
            $doc->account_creation_date,
            $doc->barcode,
            $doc->business_category,
            $doc->awb_pod,
            $doc->courier_name,
            $doc->dispatch_date,
            $doc->dispatched_by,
            $doc->courier_received_date,
            $doc->tracked_by,
            $doc->remarks,
            $doc->rejection_reason,
            $doc->lot_no,
            $doc->document_category,
            $doc->work_order_no,
            $doc->vendor_name,
            $doc->vendor_movement_date,
            $doc->file_barcode,
            $doc->box_barcode,
            $doc->date_added_to_vendor,
            $doc->status,
        ];
    }
}