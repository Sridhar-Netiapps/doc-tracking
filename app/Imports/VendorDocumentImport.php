<?php

namespace App\Imports;

use App\Models\{
    VendorDocument,
    LoanDocument,
    GoldLoanDocument,
    DtrfDocument,
    AccountOpeningDocument
};
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\{
    OnEachRow,
    WithHeadingRow,
    WithValidation,
    SkipsOnFailure,
    SkipsOnError,
    ToCollection
};
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Illuminate\Support\Facades\DB;
use Throwable;
use Carbon\Carbon;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Shared\Date;


class VendorDocumentImport implements WithHeadingRow, ToCollection, WithValidation, SkipsOnFailure, SkipsOnError
{
    use SkipsFailures;

    protected $total = 0;
    protected $success = 0;
    
    function parseExcelDate($value)
    {
        if (is_numeric($value)) {
            return date('Y-m-d', ($value - 25569) * 86400);
        } else {
            $timestamp = strtotime($value);
            return $timestamp ? date('Y-m-d', $timestamp) : null;
        }
    }

    public function collection(Collection $rows)
    {
        $table = [
            'MB LOAN' => LoanDocument::class,
            'GOLD LOAN' => GoldLoanDocument::class,
            'DTRF' => DtrfDocument::class,
            'AOF' => AccountOpeningDocument::class,
        ];
        $status = [
            'IN' => 8,
            'OUT' => 9,
            'PERMOUNT' => 10,
            'DESTROYED' => 11,
        ];
        
        foreach ($rows as $row) {
            $this->total++;
            try {
                $doc_type = !empty($row['document_type']) ? Str::upper(trim($row['document_type'])) : null;
                $doc_unique_no = !empty($row['document_unique_no']) ? Str::upper(trim($row['document_unique_no'])) : null;
                $doc_status = $status[Str::upper(trim($row['status']))];
                
                DB::beginTransaction();

                if (empty($doc_unique_no) || empty($doc_type)) {
                    throw new \Exception("Missing required fields.");
                }

                if (!isset($table[$doc_type])) {
                    throw new \Exception("Invalid document type.");
                }
                
                $document = $table[$doc_type]::where('unique_ref_no', $doc_unique_no)->whereIn('status',[5,7,8,9,10])->first();
                
                if (!$document) {
                    throw new \Exception($doc_unique_no." Document not found");
                }
                
                $document->lot_no = $row['lot_no'] ?? null;
                $document->category_of_document = $row['category_of_the_document'];
                $document->work_order_no = $row['work_order_no'];
                $document->vendor_name = $row['vendor_name'];
                $document->vendor_movement_date = !empty($row['date_of_vendor_movement']) ? $this->parseExcelDate($row['date_of_vendor_movement']) : null;
                // $document->vendor_movement_date = !empty($row['date_of_vendor_movement']) ? Carbon::createFromFormat('d/m/Y', $row['date_of_vendor_movement'])->format('Y-m-d') : null;
                // $document->vendor_movement_date = !empty($row['date_of_vendor_movement']) ? $this->parseFlexibleDate($row['date_of_vendor_movement']) : null;
                $document->file_barcode = $row['file_barcode_against_lot_no'];
                $document->box_barcode = $row['box_barcode_no'];
                $document->date_added_to_vendor = !empty($row['date_of_addition_to_vendor_data']) ? $this->parseExcelDate($row['date_of_addition_to_vendor_data']) : null;
                // $document->date_added_to_vendor = !empty($row['date_of_addition_to_vendor_data']) ? $this->parseFlexibleDate($row['date_of_addition_to_vendor_data']) : null;
                $document->status = $doc_status;
            
                if ($document->isDirty()) {
                    $document->updated_by = auth()->user()->id;
                    $document->save();
                }
                DB::commit();
                $this->success++;
            } catch (\Exception $e) {
                DB::rollBack();
                $this->failures[] = new Failure(
                    $this->total,
                    'Import',
                    [$e->getMessage()],
                    $row->toArray()
                );
            }
        }
    }

    public function rules(): array
    {
        return [
            'document_unique_no' => 'required|string',
            'document_type'      => 'required|string',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'document_unique_no.required' => 'Document Unique Number is required.',
            'document_unique_no.string'   => 'Document Unique Number must be a string.',
            'document_type.required'      => 'Document Type is required.',
            'document_type.string'        => 'Document Type must be a string.',
        ];
    }

    public function getTotal(): int
    {   
        return $this->total;
    }

    public function getSuccessCount(): int
    {
        return $this->success;
    }

    public function getFailures(): array
    {
        return $this->failures;
    }

    public function onError(Throwable $e): void
    {
        \Log::error('Excel Import Error: ' . $e->getMessage());
    }
}
