<?php

namespace App\Exports;

use App\Models\GoldLoanDocument;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class GoldLoanDocumentExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filterCallback;

    public function __construct($filterCallback)
    {
        $this->filterCallback = $filterCallback;
    }

    public function collection()
    {
        return GoldLoanDocument::where(function ($query) {
            ($this->filterCallback)($query, 'goldloan_documents');
        })->get();
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
            'Glow application ID',
            'Barcode',
            'Loan Disbursement Type',
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
            $doc->cif_id,
            $doc->account_number,
            $doc->customer_name,
            $doc->account_creation_date,
            $doc->channel,
            $doc->loan_cycle,
            $doc->glow_application_id,
            $doc->loan_disbursement_type,
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