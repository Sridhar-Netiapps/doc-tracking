<?php

namespace App\Imports;

use App\Models\VendorDocument;
use App\Models\LoanDocument;
use App\Models\GoldLoanDocument;
use App\Models\DtrfDocument;
use App\Models\AccountOpeningDocument;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Carbon;
use DB;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;
use Throwable;

class VendorDocumentImport implements OnEachRow, WithHeadingRow, WithValidation, SkipsOnFailure, SkipsOnError
{
    use SkipsFailures;

    public $failures = [];

    public function onRow(Row $row)
    {
        $table = [
            'MB Loan' => LoanDocument::class,
            'Gold Loan' => GoldLoanDocument::class,
            'DTRF' => DtrfDocument::class,
            'AOF' => AccountOpeningDocument::class,
        ];
        try {
            DB::beginTransaction();

            $update = $row->toArray();

            $table[$update['document_type']]::where('unique_ref_no', $update['document_unique_no'])->whereIn('status',[5,7])->update([
                'lot_no' => $update['lot_no'],
                'category_of_document' => $update['category_of_the_document'],
                'work_order_no' => $update['work_order_no'],
                'vendor_name' => $update['vendor_name'],
                'vendor_movement_date' => Carbon::parse($update['date_of_vendor_movement'])->format('Y-m-d'),
                'file_barcode' => $update['file_barcode_against_lot_no'],
                'box_barcode' => $update['box_barcode_no'],
                'date_added_to_vendor' => Carbon::parse($update['date_of_addition_to_vendor_data'])->format('Y-m-d'), 
                'status' => 8,
                'updated_by' => auth()->user()->id,
            ]);

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            // return response()->json(['error' => $e->getMessage()], 500);
            $this->failures[] = new Failure($row->getIndex(), 'unknown', [$e->getMessage()], $data);
        }
    }

    public function onFailure(Failure ...$failures)
    {
        $this->failures = array_merge($this->failures, $failures);
    }
}
