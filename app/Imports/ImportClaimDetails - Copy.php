<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use App\Models\InsuranceClaimDetail;
use App\Models\InsuranceNomineeDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;


class ImportClaimDetails implements ToCollection, WithStartRow, WithChunkReading, WithBatchInserts 
{
    public $rowCount = 0;
    public $insertedCount = 0;
    public $updatedCount = 0;
    public array $collectedFailures = [];
    public $failedRows = [];

  

    /**
     * Excel starts from row 2
     */
    public function startRow(): int
    {
        return 2;
    }

    /**
     * Chunk size (SAFE for 4L rows)
     */
    public function chunkSize(): int
    {
        return 1000;
    }

    /**
     * Batch insert size
     */
    public function batchSize(): int
    {
        return 1000;
    }

    /**
     * MAIN IMPORT LOGIC
     */
    public function collection(Collection $rows)
    {
        $claimRows   = [];
        $nomineeRows = [];

        foreach ($rows as $row) {
            $this->rowCount++;

            $claimRows[] = [
                'utrn'                => $row[0] ?? null,
                'region'              => $row[1] ?? null,
                'branch'              => $row[2] ?? null,
                'partner'             => $row[3] ?? null,
                'product'             => $row[4] ?? null,
                'mp_no'               => $row[5] ?? null,
                'policy_number'       => $row[6] ?? null,

                'policy_covered_date' => $this->excelDate($row[7] ?? null),
                'policy_expiry_date'  => $this->excelDate($row[8] ?? null),

                'cust_id'             => $row[9] ?? null,
                'actual_id'           => $row[10] ?? null,
                'deceased_name'       => $row[11] ?? null,
                'dob'                 => $this->excelDate($row[12] ?? null),
                'date_of_death'       => $this->excelDate($row[13] ?? null),

                'gender'              => $row[14] ?? null,
                'age'                 => $row[15] ?? null,
                'deceased'            => $row[16] ?? null,
                'intimation_date'     => $this->excelDate($row[17] ?? null),

                'place_of_death'      => $row[18] ?? null,
                'cause_of_death'      => $row[19] ?? null,
                'load_acc_id'         => $row[20] ?? null,
                'loan_tenure'         => $row[21] ?? null,
                'claim_amount'        => $row[22] ?? null,
                'nominee_name'        => $row[23] ?? null,
                'relationship'        => $row[24] ?? null,

                'doc_rec_date'        => $this->excelDate($row[25] ?? null),
                'processed_by'        => $row[26] ?? null,
                'submit_to_partner_date'    => $this->excelDate($row[27] ?? null),
                're_submit_to_partner_date' => $this->excelDate($row[28] ?? null),

                'ho_remark'           => $row[29] ?? null,
                'ho_remark2'          => $row[30] ?? null,
                'cliam_status'        => $row[31] ?? null,
                'cas_status'          => $row[32] ?? null,
                'rl_status'           => $row[33] ?? null,
                'notification_number' => $row[34] ?? null,

                'loan_amount'         => $row[35] ?? null,
                'loan_outstanding'    => $row[36] ?? null,
                'payable_to_nominee'  => $row[37] ?? null,
                'settlement_date'     => $this->excelDate($row[38] ?? null),
                'neft_rejection_date' => $this->excelDate($row[39] ?? null),
                'neft_rejection_reason' => $row[40] ?? null,
                'final_settlement_date' => $this->excelDate($row[41] ?? null),

                'utrn_mph'            => $row[42] ?? null,
                'utrn_nominee'        => $row[43] ?? null,
                'recovery_status'     => $row[44] ?? null,
                'bounced_chq_no'      => $row[45] ?? null,
                'chq_deposit_date'    => $this->excelDate($row[46] ?? null),
                'bounced_chq_date'    => $this->excelDate($row[47] ?? null),
                'bounced_chq_reason'  => $row[48] ?? null,
                'recovered_amount'    => $row[49] ?? null,

                'write_off_rec'       => $this->excelDate($row[50] ?? null),
                'write_off_status'    => $row[51] ?? null,
                'handed_to_bh'        => $row[52] ?? null,
                'handed_to_credit'    => $row[53] ?? null,

                'ho_employee_id'      => Auth::user()->employee_id,
                
            ];

            $nomineeRows[] = [
                'utrn'             => $row[0] ?? null,
                'nominee_name_bank'=> $row[54] ?? null,
                'bank_name'        => $row[55] ?? null,
                'acc_number'       => $row[56] ?? null,
                'ifsc'             => $row[57] ?? null,
                'branch_name'      => $row[58] ?? null,
                'spdc_bank_name'   => $row[59] ?? null,
                'spdc_chk_no'      => $row[60] ?? null,
                'courier_name'     => $row[61] ?? null,
                'pod_no'           => $row[62] ?? null,
                'nominee_number'   => $row[63] ?? null,
                'bo_remarks'       => $row[64] ?? null,
                'bo_maker'         => $row[65] ?? null,
                'bo_checker'       => $row[66] ?? null,
                'ack_rec_date'     => $this->excelDate($row[67] ?? null),
                'spdc_rec_date'    => $this->excelDate($row[68] ?? null),
                'pkt_no'           => $row[69] ?? null,
               
            ];
        }

        DB::transaction(function () use ($claimRows, $nomineeRows) {

            InsuranceClaimDetail::insert($claimRows);

            // Map UTRN → ID
            $claimIds = InsuranceClaimDetail::whereIn('utrn', array_column($claimRows, 'utrn'))
                        ->pluck('id', 'utrn');

            foreach ($nomineeRows as &$n) {
                $n['insurance_claim_details_id'] = $claimIds[$n['utrn']] ?? null;
                unset($n['utrn']);
            }

            InsuranceNomineeDetail::insert($nomineeRows);
        });
    }

    /**
     * Excel Date Helper
     */
    public function getRowCount(): int
    {
        return $this->rowCount;
    }

    public function getInsertedCount()
    {
        return $this->insertedCount;
    }

    public function getUpdatedCount()
    {
        return $this->updatedCount;
    }


    private function excelDate($value)
    {
        if ($value === null || $value === '') {
            return null;
        }

        // ✅ Excel numeric date
        if (is_numeric($value)) {
            return ExcelDate::excelToDateTimeObject($value)
                ->format('Y-m-d');
        }

        // ✅ Clean string
        $value = trim($value);

        // ✅ Explicit format: 01 Jan 2026
        try {
            return Carbon::createFromFormat('d M Y', $value)
                ->format('Y-m-d');
        } catch (\Exception $e) {
            // fallback
        }

        // ✅ Last fallback (ISO / Y-m-d / etc)
        try {
            return Carbon::parse($value)
                ->format('Y-m-d');
        } catch (\Exception $e) {
            return null; // 🚫 never return 1970
        }
    }
}
