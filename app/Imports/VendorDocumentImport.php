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

class VendorDocumentImport implements WithHeadingRow, ToCollection, WithValidation, SkipsOnFailure, SkipsOnError
{
    use SkipsFailures;

    protected $total = 0;
    protected $success = 0;


    public function collection(Collection $rows)
    {
        $table = [
            'MB Loan' => LoanDocument::class,
            'Gold Loan' => GoldLoanDocument::class,
            'DTRF' => DtrfDocument::class,
            'AOF' => AccountOpeningDocument::class,
        ];
        $status = [
            'In' => 8,
            'Out' => 9,
            'Permount' => 10,
            'Destroyed' => 11,
        ];

        foreach ($rows as $row) {
            // dd($row);
            $this->total++;
            try {
                DB::beginTransaction();
                if (empty($row['document_unique_no']) || empty($row['document_type'])) {
                    throw new \Exception("Missing required fields.");
                }

                if (!isset($table[$row['document_type']])) {
                    throw new \Exception("Invalid document type.");
                }

                // VendorDocument::where('document_unique_no', $row['document_unique_no'])
                $document = $table[$row['document_type']]::where('unique_ref_no', $row['document_unique_no'])->where('status','>',5)->first();
                if (!$document) {
                    throw new \Exception($row['document_unique_no']." Document not found");
                }

                $document->lot_no = $row['lot_no'] ?? null;
                $document->category_of_document = $row['category_of_the_document'];
                $document->work_order_no = $row['work_order_no'];
                $document->vendor_name = $row['vendor_name'];
                $document->vendor_movement_date = Carbon::parse($row['date_of_vendor_movement'])->format('Y-m-d');
                $document->file_barcode = $row['file_barcode_against_lot_no'];
                $document->box_barcode = $row['box_barcode_no'];
                $document->date_added_to_vendor = Carbon::parse($row['date_of_addition_to_vendor_data'])->format('Y-m-d');
                $document->status = $status[$row['status']] ?? null;
                
                // dd($document);
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

    // public function onRow(Row $row)
    // {
    //     $table = [
    //         'MB Loan' => LoanDocument::class,
    //         'Gold Loan' => GoldLoanDocument::class,
    //         'DTRF' => DtrfDocument::class,
    //         'AOF' => AccountOpeningDocument::class,
    //     ];
    //     $status = [
    //         'In' => 8,
    //         'Out' => 9,
    //         'Permount' => 10,
    //         'Destroyed' => 11,
    //     ];
    //     try {
    //         DB::beginTransaction();

    //         $update = $row->toArray();

    //         $table[$update['document_type']]::where('unique_ref_no', $update['document_unique_no'])->whereIn('status',[5,7])->update([
    //             'lot_no' => $update['lot_no'],
    //             'category_of_document' => $update['category_of_the_document'],
    //             'work_order_no' => $update['work_order_no'],
    //             'vendor_name' => $update['vendor_name'],
    //             'vendor_movement_date' => Carbon::parse($update['date_of_vendor_movement'])->format('Y-m-d'),
    //             'file_barcode' => $update['file_barcode_against_lot_no'],
    //             'box_barcode' => $update['box_barcode_no'],
    //             'date_added_to_vendor' => Carbon::parse($update['date_of_addition_to_vendor_data'])->format('Y-m-d'), 
    //             'status' => $status[$update['status']],
    //             'updated_by' => auth()->user()->id,
    //         ]);

    //         DB::commit();

    //         return response()->json(['success' => true]);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         // return response()->json(['error' => $e->getMessage()], 500);
    //         $this->failures[] = new Failure($row->getIndex(), 'unknown', [$e->getMessage()], $data);
    //     }
    // }

    // public function onFailure(Failure ...$failures)
    // {
    //     $this->failures = array_merge($this->failures, $failures);
    // }
}
