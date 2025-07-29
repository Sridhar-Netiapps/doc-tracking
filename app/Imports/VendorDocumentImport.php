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

class VendorDocumentImport implements WithHeadingRow, ToCollection, WithValidation, SkipsOnFailure, SkipsOnError
{
    use SkipsFailures;

    protected $total = 0;
    protected $success = 0;


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
                $doc_type = Str::upper(trim($row['document_type']));
                $doc_unique_no = Str::upper(trim($row['document_unique_no']));
                $doc_status = $status[Str::upper(trim($row['status']))];
                
                DB::beginTransaction();
                if (empty($doc_unique_no) || empty($doc_type)) {
                    throw new \Exception("Missing required fields.");
                }

                if (!isset($table[$doc_type])) {
                    throw new \Exception("Invalid document type.");
                }

                // VendorDocument::where('document_unique_no', $doc_unique_no)
                $document = $table[$doc_type]::where('unique_ref_no', $doc_unique_no)->whereIn('status',[5,7,8,9,10])->first();
                // dd($document);
                if (!$document) {
                    throw new \Exception($doc_unique_no." Document not found");
                }

                $document->lot_no = $row['lot_no'] ?? null;
                $document->category_of_document = $row['category_of_the_document'];
                $document->work_order_no = $row['work_order_no'];
                $document->vendor_name = $row['vendor_name'];
                $document->vendor_movement_date = Carbon::parse($row['date_of_vendor_movement'])->format('Y-m-d');
                $document->file_barcode = $row['file_barcode_against_lot_no'];
                $document->box_barcode = $row['box_barcode_no'];
                $document->date_added_to_vendor = Carbon::parse($row['date_of_addition_to_vendor_data'])->format('Y-m-d');
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
        return [];
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
