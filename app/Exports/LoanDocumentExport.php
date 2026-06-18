<?php

namespace App\Exports;

use App\Exports\Concerns\AppliesDocumentExportFilters;
use App\Exports\Concerns\ForceNumericStringAsText;
use App\Models\LoanDocument;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithCustomChunkSize;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use Log;

class LoanDocumentExport extends DefaultValueBinder implements FromQuery, WithHeadings, WithMapping, WithCustomChunkSize, WithCustomValueBinder
{
    use AppliesDocumentExportFilters;
    use ForceNumericStringAsText;

    protected array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        \Log::info('Document export function');
        $query = LoanDocument::query()
            ->select([
                'id',
                'dispatch_id',
                'unique_ref_no',
                'region',
                'branch_code',
                'branch_name',
                'cif_id',
                'account_number',
                'loan_cycle',
                'customer_name',
                'account_creation_date',
                'channel',
                'glow_application_id',
                'loan_amount',
                'loan_disbursement_type',
                'business_category',
                'barcode',
                'status',
                'reason',
                'lot_no',
                'category_of_document',
                'work_order_no',
                'vendor_name',
                'vendor_movement_date',
                'file_barcode',
                'box_barcode',
                'date_added_to_vendor',
            ])
            ->with([
                'dispatch.courierName',
                'dispatch.modifier',
                'getDispatchedDetails.creator',
                'statusName',
                'getReceivedDetails.newStatus',
                'getReceivedDetails.creator',
            ]);

        return $this->applyDocumentExportFilters($query, 'loan_documents', $this->filters);
    }

    public function chunkSize(): int
    {
        return 1000;
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
            (string) $doc->account_number,
            // (string) trim($doc->account_number),
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
            $doc->status >= 4 ? (optional($doc->getDispatchedDetails)->current_status == 4 ? optional($doc->getDispatchedDetails->creator)->employee_id . ' - ' . optional($doc->getDispatchedDetails->creator)->first_name.' '.optional($doc->getDispatchedDetails->creator)->last_name : '-') : '-',
            $doc->status >= 4 ? (optional($doc->dispatch)->verified_at ? date('d-m-Y', strtotime($doc->dispatch->verified_at)) : '-') : '-',
            $doc->status >= 4 ? (optional($doc->dispatch)->status == 12 ? optional($doc->dispatch->modifier)->employee_id . ' - ' . optional($doc->dispatch->modifier)->first_name.' '.optional($doc->dispatch->modifier)->last_name : '-') : '-',
            $doc->status >= 4 ? optional($doc->statusName)->name : '-',
            $doc->status >= 4 ? optional(optional($doc->getReceivedDetails)->newStatus)->name : '-',
            (in_array($doc->status, [6,7]) ? ($doc->reason) : '-'),
            $doc->status >= 4 ? (optional($doc->getReceivedDetails)->created_at ? date('d-m-Y', strtotime($doc->getReceivedDetails->created_at)) : '-') : '-',
            $doc->status >= 4 ? (optional($doc->getReceivedDetails)->current_status >= 4 ? optional($doc->getReceivedDetails->creator)->employee_id . ' - ' . optional($doc->getReceivedDetails->creator)->first_name.' '.optional($doc->getReceivedDetails->creator)->last_name : '-') : '-',
            $doc->lot_no,
            $doc->category_of_document,
            $doc->work_order_no,
            $doc->vendor_name,
            $doc->vendor_movement_date != null ? date('d-m-Y', strtotime($doc->vendor_movement_date)) : '-',
            $doc->file_barcode,
            $doc->box_barcode,
            $doc->date_added_to_vendor != null ? date('d-m-Y', strtotime($doc->date_added_to_vendor)) : '-',
            optional($doc->statusName)->name ?? '-',
        ];
    }
}
