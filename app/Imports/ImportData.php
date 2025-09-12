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
use Illuminate\Validation\Rule;


class ImportData implements WithHeadingRow, ToCollection, WithValidation, SkipsOnFailure, SkipsOnError
{
    use SkipsFailures;

    protected $total = 0;
    protected $success = 0;
    protected $doc_type;


    public function __construct($doc_type)
    {
        $this->doc_type = $doc_type;
        // dd($doc_type);
    }
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
        // dd($rows);
        $table = [
            'loan' => LoanDocument::class,
            'goldloan' => GoldLoanDocument::class,
            'dtrf' => DtrfDocument::class,
            'aof' => AccountOpeningDocument::class,
        ];
        $status = [
            'PENDING' => 1,
            'INDRAFT' => 2,
            'AWAITING CHECKER APPROVAL' => 3,
            'DISPATCHED' => 4,
            'RECEIVED' => 5,
            'REJECTED' => 6,
            'RECEIVED WITH QUERY' => 7,
            'IN' => 8,
            'OUT' => 9,
            'PERMOUNT' => 10,
            'DESTROYED' => 11,
        ];
        
        foreach ($rows as $row) {
            $this->total++;
            try {
                // $doc_type = !empty($row['document_type']) ? Str::upper(trim($row['document_type'])) : null;
                $doc_unique_no = !empty($row['unique_ref_no']) ? Str::upper(trim($row['unique_ref_no'])) : null;
                // $doc_status = $status[Str::upper(trim($row['status']))];
                $doc_status = isset($row['status']) ? ($status[Str::upper(trim($row['status']))] ?? null) : null;


                DB::beginTransaction();

                // if (empty($doc_unique_no) || empty($doc_type)) {
                //     throw new \Exception("Missing required fields.");
                // }

                if (!isset($table[$this->doc_type])) {
                    throw new \Exception("Invalid document type.");
                }
                
                // $document = $table[$this->doc_type]::where('unique_ref_no', $doc_unique_no)->whereIn('status',[5,7,8,9,10])->first();
                $document = new $table[$this->doc_type];
                // if (!$document) {
                //     throw new \Exception($doc_unique_no." Document not found");
                // }
                $document->unique_ref_no = $row['unique_ref_no'] ?? null;
                $document->region = $row['region'] ?? null;
                $document->branch_code = $row['branch_code'] ?? null;
                $document->branch_name = $row['branch_name'] ?? null;
                $document->account_creation_date = !empty($row['account_creation_date']) ? $this->parseExcelDate($row['account_creation_date']) : null;
                $document->barcode = $row['barcode'] ?? null;
                $document->business_category = $row['business_category'] ?? null;
                $document->reason = $row['reason'] ?? null;
                $document->lot_no = $row['lot_no'] ?? null;
                $document->category_of_document = $row['category_of_document'] ?? null;
                $document->work_order_no = $row['work_order_no'] ?? null;
                $document->vendor_name = $row['vendor_name'] ?? null;
                $document->vendor_movement_date = !empty($row['vendor_movement_date']) ? $this->parseExcelDate($row['vendor_movement_date']) : null;
                $document->file_barcode = $row['file_barcode'] ?? null;
                $document->box_barcode = $row['box_barcode'] ?? null;
                $document->date_added_to_vendor = !empty($row['date_added_to_vendor']) ? $this->parseExcelDate($row['date_added_to_vendor']) : null;
                $document->status = $doc_status;

                if($this->doc_type != 'dtrf'){
                    $document->cif_id = $row['cif_id'] ?? null;
                    $document->account_number = $row['account_number'] ?? null;
                    $document->customer_name = $row['customer_name'] ?? null;
                    $document->channel = $row['channel'] ?? null;
                }
                if($this->doc_type == 'loan'){
                    $document->loan_cycle = $row['loan_cycle'] ?? null;
                    $document->glow_application_id = $row['glow_application_id'] ?? null;
                    $document->loan_amount = $row['loan_amount'] ?? null;
                    $document->loan_disbursement_type = $row['loan_disbursement_type'] ?? null;
                }
                if($this->doc_type == 'goldloan'){
                    $document->loan_amount = $row['loan_amount'] ?? null;  
                }
                if($this->doc_type == 'aof'){
                    $document->scheme = $row['scheme'] ?? null;
                    $document->pgk_no = $row['pgk_no'] ?? null;
                    $document->type_of_account_opening = $row['type_of_account_opening'] ?? null;
                }
            
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
            'unique_ref_no'           => ['required', 'string'],
            // 'doc_type'                => ['required', 'string'],
            'region'                  => ['required', 'string'],
            'branch_code'             => ['required'],
            'branch_name'             => ['required'],
            'account_creation_date'   => ['required', 'date'],
            'business_category'       => ['required'],
            'status'                  => ['required'],
    
            // Conditional: required if doc_type is NOT 'dtrf'
            'cif_id'                  => Rule::requiredIf(fn() => $this->doc_type !== 'dtrf'),
            'account_number'          => Rule::requiredIf(fn() => $this->doc_type !== 'dtrf'),
            'customer_name'           => Rule::requiredIf(fn() => $this->doc_type !== 'dtrf'),
            'channel'                 => Rule::requiredIf(fn() => $this->doc_type !== 'dtrf'),
    
            // Conditional: required if doc_type is 'loan'
            'loan_cycle'              => Rule::requiredIf(fn() => $this->doc_type === 'loan'),
            'glow_application_id'     => Rule::requiredIf(fn() => $this->doc_type === 'loan'),
            'loan_amount'             => Rule::requiredIf(fn() => in_array($this->doc_type, ['loan', 'goldloan'])),
            'loan_disbursement_type'  => Rule::requiredIf(fn() => $this->doc_type === 'loan'),
    
            // Conditional: required if doc_type is 'aof'
            'scheme'                  => Rule::requiredIf(fn() => $this->doc_type === 'aof'),
            'pgk_no'                  => Rule::requiredIf(fn() => $this->doc_type === 'aof'),
            'type_of_account_opening' => Rule::requiredIf(fn() => $this->doc_type === 'aof'),
        ];
    }

    public function customValidationMessages()
    {
        return [
            'unique_ref_no.required'           => 'Document Unique Number is required.',
            'region.required'                  => 'Region is required.',
            'branch_code.required'             => 'Branch Code is required.',
            'branch_name.required'             => 'Branch Name is required.',
            'account_creation_date.required'   => 'Account Creation Date is required.',
            'account_creation_date.date'       => 'Account Creation Date must be a valid date.',
            'business_category.required'       => 'Business Category is required.',
            'status.required'                  => 'Status is required.',
    
            'cif_id.required'                  => 'CIF ID is required for this document type.',
            'account_number.required'          => 'Account Number is required for this document type.',
            'customer_name.required'           => 'Customer Name is required for this document type.',
            'channel.required'                 => 'Channel is required for this document type.',
    
            'loan_cycle.required'              => 'Loan Cycle is required for Loan documents.',
            'glow_application_id.required'     => 'Glow Application ID is required for Loan documents.',
            'loan_amount.required'             => 'Loan Amount is required for Loan or Gold Loan documents.',
            'loan_disbursement_type.required'  => 'Loan Disbursement Type is required for Loan documents.',
    
            'scheme.required'                  => 'Scheme is required for AOF documents.',
            'pgk_no.required'                  => 'PGK No is required for AOF documents.',
            'type_of_account_opening.required' => 'Type of Account Opening is required for AOF documents.',
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
