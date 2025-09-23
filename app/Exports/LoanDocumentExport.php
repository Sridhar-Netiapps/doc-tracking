<?php

namespace App\Exports;

use App\Models\LoanDocument;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LoanDocumentExport implements FromCollection, WithHeadings, WithMapping
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
            'Loan Cycle',
            'Customer Name',
            'Account Creation Date',
            'Channel',
            'Glow application ID',
            'Loan Amount',
            'Loan Disbursement Type',
            'Business Category',
            'Barcode',
            'AWB/POD',
            'Courier name',
            'Dispatch Date',
            'Dispatched By (User ID)',
            'Courier Received date @ Mail Room',
            'Tracked by (User ID)',
            'Remarks',
            'RO Received Status',
            'Reasons',
            'RO Received Date',
            'RO Tracked by (User ID)',
            'Lot No',
            'Document Category',
            'Work Order No',
            'Vendor Name',
            'Date of Vendor Movement',
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
            $doc->account_number.' ',
            $doc->loan_cycle,
            $doc->customer_name,
            $doc->account_creation_date != null ? date('d-m-Y', strtotime($doc->account_creation_date)) : '-',
            $doc->channel,
            $doc->glow_application_id,
            $doc->loan_amount,
            $doc->loan_disbursement_type,
            $doc->business_category,
            $doc->barcode,
            $doc->status >= 4 ? optional($doc->dispatch)->awb_pod : '-',
            $doc->status >= 4 ? optional(optional($doc->dispatch)->courierName)->name : '-',
            $doc->status >= 4 ? (optional($doc->dispatch)->dispatch_date ? date('d-m-Y', strtotime($doc->dispatch->dispatch_date)) : '-') : '-',
            $doc->status >= 4 ? (optional($doc->dispatch)->status >= 4 ? optional($doc->dispatch->dispatcher)->employee_id . ' - ' . optional($doc->dispatch->dispatcher)->first_name : '-') : '-',
            $doc->status >= 4 ? (in_array(optional($doc->dispatch)->status, [5,6,7]) ? date('d-m-Y', strtotime($doc->dispatch->updated_at))  : '-') : '-',
            $doc->status >= 4 ? (optional($doc->dispatch)->status == 12 ? optional($doc->dispatch->modifier)->employee_id . ' - ' . optional($doc->dispatch->modifier)->first_name : '-') : '-',
            $doc->status >= 4 ? optional($doc->statusName)->name : '-',
            $doc->status >= 4 ? optional(optional($doc->getReceivedDetails)->newStatus)->name : '-',
            $doc->reason != null ? ($doc->reason) : '-',
            $doc->status >= 4 ? (optional($doc->getReceivedDetails)->created_at ? date('d-m-Y', strtotime($doc->getReceivedDetails->created_at)) : '-') : '-',
            $doc->status >= 4 ? (optional($doc->getReceivedDetails)->current_status >= 4 ? optional($doc->getReceivedDetails->creator)->employee_id . ' - ' . optional($doc->getReceivedDetails->creator)->first_name : '-') : '-',
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
