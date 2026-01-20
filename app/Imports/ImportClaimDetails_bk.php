<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\InsuranceClaimDetail;
use App\Models\InsuranceNomineeDetail;
use Auth;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use DateTime;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\Importable;
use Throwable;
use Illuminate\Support\Facades\Log;


class ImportClaimDetails implements ToCollection, WithStartRow, WithChunkReading, SkipsOnFailure, SkipsOnError , ShouldQueue
{
    use Importable, SkipsFailures, SkipsErrors;

    public $chunkSize = 1000;
    public $rowCount = 0;
    public $insertedCount = 0;
    public $updatedCount = 0;
    public array $failedRows = [];
    public array $collectedFailures = [];
    private $chunkNumber = 0;

    public function startRow(): int
    {
        return 2;
    }

    public function chunkSize(): int
    {
        return $this->chunkSize;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            ++$this->rowCount;
            $this->chunkNumber++;


            $row = $this->ensureMissingIndexesAreNull($row, range(6, 69));

            $errors = $this->validateRow($row);
            if (!empty($errors)) {
                $this->failedRows[] = [
                    'row' => $this->rowCount,
                    'data' => $row->toArray(),
                    'errors' => $errors,
                ];
                continue;
            }

           // $utrn = $row[0] ?? 'INS_CLM'.rand(100000, 999999);
            $utrn = $row[0] ?? null;

            // If empty or duplicate → generate a new one
            if (!$utrn || InsuranceClaimDetail::where('utrn', $utrn)->exists()) {

                do {
                    $utrn = 'INS_CLM'.rand(100000, 999999);
                } while (InsuranceClaimDetail::where('utrn', $utrn)->exists());
            }

            // Fetch existing claim if exists
            $existingClaim = InsuranceClaimDetail::where('utrn', $utrn)->first();

            $claimData = [];

            $fields = [
                'region' => 1, 'branch' => 2, 'partner' => 3, 'product' => 4,
                'mp_no' => 5, 'policy_number' => 6, 'policy_covered_date' => 7,
                'cust_id' => 9, 'actual_id' => 10, 'deceased_name' => 11,
                'dob' => 12, 'date_of_death' => 13, 'gender' => 14,
                'deceased' => 16, 'intimation_date' => 17, 'place_of_death' => 18,
                'cause_of_death' => 19, 'load_acc_id' => 20, 'loan_tenure' => 21,
                'claim_amount' => 22, 'nominee_name' => 23, 'relationship' => 24,
                'doc_rec_date' => 25, 'processed_by' => 26, 'submit_to_partner_date' => 27,
                're_submit_to_partner_date' => 28, 'ho_remark' => 29, 'ho_remark2' => 30,
                'cliam_status' => 31, 'cas_status' => 32, 'rl_status' => 33, 'notification_number' => 34,
                'loan_amount' => 35, 'loan_outstanding' => 36, 'payable_to_nominee' => 37,
                'settlement_date' => 38, 'neft_rejection_date' => 39, 'neft_rejection_reason' => 40,
                'final_settlement_date' => 41, 'utrn_mph' => 42, 'utrn_nominee' => 43,
                'recovery_status' => 44, 'bounced_chq_no' => 45, 'chq_deposit_date' => 46,
                'bounced_chq_date' => 47, 'bounced_chq_reason' => 48, 'recovered_amount' => 49,
                'write_off_rec' => 50, 'write_off_status' => 51, 'handed_to_bh' => 52, 'handed_to_credit' => 53, 'updated_at' => 70
            ];

            foreach ($fields as $colName => $colIndex) {
                if (!empty($row[$colIndex])) {
                    if (in_array($colName, ['policy_covered_date','dob','date_of_death','intimation_date','submit_to_partner_date','re_submit_to_partner_date','doc_rec_date','settlement_date','neft_rejection_date','final_settlement_date','chq_deposit_date','bounced_chq_date','write_off_rec'])) {
                        $claimData[$colName] = is_numeric($row[$colIndex])
                            ? ExcelDate::excelToDateTimeObject($row[$colIndex])->format('Y-m-d')
                            : $row[$colIndex];
                    } else {
                        $claimData[$colName] = $row[$colIndex];
                    }
                } elseif ($existingClaim) {
                    // keep old value if update and Excel cell is empty
                    $claimData[$colName] = $existingClaim->$colName;
                }
            }

            // Calculate policy_expiry_date if both start date and tenure exist
            if (!empty($claimData['policy_covered_date']) && !empty($claimData['loan_tenure'])) {
                $start = new DateTime($claimData['policy_covered_date']);
                $start->modify("+" . (int)$claimData['loan_tenure'] . " months");
                $claimData['policy_expiry_date'] = $start->format('Y-m-d');
            }

            // Calculate age if dob exists
            if (!empty($claimData['dob'])) {
                $claimData['age'] = $this->calculateAge($claimData['dob']);
            }

           /* $claimData['ho_employee_id'] = Auth::user()->employee_id;
            $claimData['latest_editor'] = Auth::user()->employee_id;*/
            $claimData['ho_employee_id'] = 'Netiapps07';
            $claimData['latest_editor'] = 'Netiapps07';

            if ($existingClaim) {
                $existingClaim->update($claimData);
                $this->updatedCount++;
            } else {
                $claimData['utrn'] = $utrn;
                $claimData['created_at'] = now();
                $claimData['updated_at'] = now();
                $newClaim = InsuranceClaimDetail::create($claimData);
                $this->insertedCount++;
            }

            $claimId = $existingClaim->id ?? $newClaim->id;

            // Handle Nominee Details
            $nomineeFields = [
                'nominee_name_bank' => 54, 'bank_name' => 55, 'acc_number' => 56,
                'ifsc' => 57, 'branch_name' => 58, 'spdc_bank_name' => 59,
                'spdc_chk_no' => 60, 'courier_name' => 61, 'pod_no' => 62,
                'nominee_number' => 63, 'bo_remarks' => 64, 'bo_maker' => 65,
                'bo_checker' => 66, 'ack_rec_date' => 67, 'spdc_rec_date' => 68, 'pkt_no' => 69
            ];

            $existingNominee = InsuranceNomineeDetail::where('insurance_claim_details_id', $claimId)->first();
            $nomineeData = ['insurance_claim_details_id' => $claimId];

            foreach ($nomineeFields as $colName => $colIndex) {
                if (!empty($row[$colIndex])) {
                    if (in_array($colName, ['ack_rec_date','spdc_rec_date'])) {
                        $nomineeData[$colName] = is_numeric($row[$colIndex])
                            ? ExcelDate::excelToDateTimeObject($row[$colIndex])->format('Y-m-d')
                            : $row[$colIndex];
                    } else {
                        $nomineeData[$colName] = $row[$colIndex];
                    }
                } elseif ($existingNominee) {
                    $nomineeData[$colName] = $existingNominee->$colName;
                }
            }

            if ($existingNominee) {
                $existingNominee->update($nomineeData);
            } else {
                $nomineeData['created_at'] = now();
                $nomineeData['updated_at'] = now();
                InsuranceNomineeDetail::create($nomineeData);
            }
        }
        Log::info("Finished chunk #{$this->chunkNumber} | Total rows: {$this->rowCount}");
    }

    private function validateRow($row)
    {
        $errors = [];
        foreach ($row as $key => $value) {
            if (is_string($value)) {
                if (preg_match('/<script\b[^>]*>(.*?)<\/script>/i', $value)) {
                    $errors[] = 'Script tags are not allowed';
                } elseif (!preg_match('/^[a-zA-Z0-9\s,.\-_]*$/', $value)) {
                    $errors[] = 'Special characters are not allowed';
                }
            }
        }
        return $errors;
    }

    private function calculateAge($dob)
    {
        $birthDate = is_numeric($dob) ? ExcelDate::excelToDateTimeObject($dob) : new DateTime($dob);
        $today = new DateTime();
        return $today->diff($birthDate)->y;
    }

    protected function ensureMissingIndexesAreNull($row, array $indexes): array
    {
        $row = $row instanceof \Illuminate\Support\Collection ? $row->toArray() : $row;

        foreach ($indexes as $index) {
            if (!array_key_exists($index, $row)) {
                $row[$index] = null;
            }
        }

        return $row;
    }


    public function onFailure(...$failures)
    {
        $this->collectedFailures = array_merge($this->collectedFailures, $failures);
        foreach ($failures as $failure) {
            $this->failedRows[] = [
                'row' => $failure->row(),
                'data' => $failure->values(),
                'error' => implode(', ', $failure->errors()),
            ];
        }
    }

    public function onError(Throwable $e)
    {
        Log::error("Import error: ".$e->getMessage());
    }

    public function getRowCount(): int
    {
        return $this->rowCount;
    }

    public function getInsertedCount(): int
    {
        return $this->insertedCount;
    }

    public function getUpdatedCount(): int
    {
        return $this->updatedCount;
    }
}

/*
<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\InsuranceClaimDetail;
use App\Models\InsuranceNomineeDetail;
use App\Models\InsuranceChecklist;
use Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Validators\Failure;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

use Throwable;

class ImportClaimDetails implements ToModel, WithStartRow, SkipsOnFailure, SkipsOnError
{
    /**s
    * @param Collection $collection
    */
   
    public $rowCount = 0;
    public $insertedCount = 0;
    public $updatedCount = 0;
    public array $collectedFailures = [];
    public $failedRows = [];


     use Importable, SkipsFailures, SkipsErrors;

     public function startRow(): int
    {
        return 2;
    } 
    

    
    public function model(array $row)
    {
       ++$this->rowCount;

       $row = $this->ensureMissingIndexesAreNull($row, [6, 13]);
       
     
          $claimDetail = new InsuranceClaimDetail;
          $utrn = $row['0'];
          $claimDetail->utrn = $utrn;
          $claimDetail->ho_employee_id = Auth::user()->employee_id; 
          $this->insertedCount++;
   
          
            if(!empty($row['1'])){ $claimDetail->region = $row['1']; } 
            if(!empty($row['2'])){ $claimDetail->branch = $row['2']; } 
            if(!empty($row['3'])){ $claimDetail->partner = $row['3']; } 
            if(!empty($row['4'])){ $claimDetail->product = $row['4']; } 
            if(!empty($row['5'])){ $claimDetail->mp_no = $row['5']; } 
            if(!empty($row['6'])){ $claimDetail->policy_number = $row['6']; } 
            if(!empty($row['7'])){ $claimDetail->policy_covered_date = is_numeric($row['7'])? Date::excelToDateTimeObject($row['7'])->format('Y-m-d'): $row['7'];} 
            $claimDetail->policy_expiry_date = $exp_date ?? (is_numeric($row['8'])? Date::excelToDateTimeObject($row['8'])->format('Y-m-d'): $row['8']);
            if(!empty($row['9'])){ $claimDetail->cust_id = $row['9']; } 
            if(!empty($row['10'])){ $claimDetail->actual_id = $row['10']; } 
            if(!empty($row['11'])){ $claimDetail->deceased_name = $row['11']; } 
            if(!empty($row['12'])){$claimDetail->dob = is_numeric($row['12'])? Date::excelToDateTimeObject($row['12'])->format('Y-m-d'): $row['12'];}
            if(!empty(trim($row['13']))){$claimDetail->date_of_death = is_numeric($row['13'])? Date::excelToDateTimeObject($row['13'])->format('Y-m-d'): $row['13'];}
            if(!empty($row['14'])){ $claimDetail->gender = $row['14']; }
            if(!empty($row['15'])){ $claimDetail->age = $myage ?? $row['15']; }  
            if(!empty($row['16'])){ $claimDetail->deceased = $row['16']; }
            if(!empty($row['17'])){$claimDetail->intimation_date = is_numeric($row['17'])? Date::excelToDateTimeObject($row['17'])->format('Y-m-d'): $row['17'];}
            if(!empty($row['18'])){ $claimDetail->place_of_death = $row['18']; } 
            if(!empty($row['19'])){ $claimDetail->cause_of_death = $row['19']; } 
            if(!empty($row['20'])){ $claimDetail->load_acc_id = $row['20']; } 
            if(!empty($row['21'])){ $claimDetail->loan_tenure = $row['21']; }
            if(!empty($row['22'])){ $claimDetail->claim_amount = $row['22']; } 
            if(!empty($row['23'])){ $claimDetail->nominee_name = $row['23']; } 
            if(!empty($row['24'])){ $claimDetail->relationship = $row['24'];} 
            
            if(!empty($row['25'])){$claimDetail->doc_rec_date = is_numeric($row['25'])? Date::excelToDateTimeObject($row['25'])->format('Y-m-d'): $row['25'];}
            if(!empty($row['26'])){ $claimDetail->processed_by = $row['26']; } 
            if(!empty($row['27'])){$claimDetail->submit_to_partner_date = is_numeric($row['27'])? Date::excelToDateTimeObject($row['27'])->format('Y-m-d'): $row['27'];}
            if(!empty($row['28'])){$claimDetail->re_submit_to_partner_date = is_numeric($row['28'])? Date::excelToDateTimeObject($row['28'])->format('Y-m-d'): $row['28'];}
            if(!empty($row['29'])){ $claimDetail->ho_remark = $row['29']; } 
            if(!empty($row['30'])){ $claimDetail->ho_remark2 = $row['30']; }
            if(!empty($row['31'])){ $claimDetail->cliam_status = $row['31']; } 
            if(!empty($row['32'])){ $claimDetail->cas_status = $row['32']; } 
            if(!empty($row['33'])){ $claimDetail->rl_status = $row['33']; } 
            if(!empty($row['34'])){ $claimDetail->notification_number = $row['34']; }  
            
            
            if(!empty($row['35'])){ $claimDetail->loan_amount = $row['35']; } 
            if(!empty($row['36'])){ $claimDetail->loan_outstanding = $row['36']; } 
            if(!empty($row['37'])){ $claimDetail->payable_to_nominee = $row['37']; } 
            if(!empty($row['38'])){$claimDetail->settlement_date = is_numeric($row['38'])? Date::excelToDateTimeObject($row['38'])->format('Y-m-d'): $row['38'];}
            if(!empty($row['39'])){$claimDetail->neft_rejection_date = is_numeric($row['39'])? Date::excelToDateTimeObject($row['39'])->format('Y-m-d'): $row['39'];}
            if(!empty($row['40'])){ $claimDetail->neft_rejection_reason = $row['40']; } 
            if(!empty($row['41'])){$claimDetail->final_settlement_date = is_numeric($row['41'])? Date::excelToDateTimeObject($row['41'])->format('Y-m-d'): $row['41'];}
            if(!empty($row['42'])){ $claimDetail->utrn_mph = $row['42']; }  
            if(!empty($row['43'])){ $claimDetail->utrn_nominee = $row['43']; }   

            if(!empty($row['44'])){ $claimDetail->recovery_status = $row['44']; } 
            if(!empty($row['45'])){ $claimDetail->bounced_chq_no = $row['45']; } 
            if(!empty($row['46'])){$claimDetail->chq_deposit_date = is_numeric($row['46'])? Date::excelToDateTimeObject($row['46'])->format('Y-m-d'): $row['46'];}
            if(!empty($row['47'])){$claimDetail->bounced_chq_date = is_numeric($row['47'])? Date::excelToDateTimeObject($row['47'])->format('Y-m-d'): $row['47'];}
            if(!empty($row['48'])){ $claimDetail->bounced_chq_reason = $row['48']; }
            if(!empty($row['49'])){ $claimDetail->recovered_amount = $row['49']; }
            //if(!empty($row['10'])){ $claimDetail->policy_covered_date = $row['10']; } 
            
            if(!empty($row['50'])){$claimDetail->write_off_rec = is_numeric($row['50'])? Date::excelToDateTimeObject($row['50'])->format('Y-m-d'): $row['50'];} 
            if(!empty($row['51'])){ $claimDetail->write_off_status = $row['51']; } 
            if(!empty($row['52'])){ $claimDetail->handed_to_bh = $row['52']; } 
            if(!empty($row['53'])){ $claimDetail->handed_to_credit = $row['53']; } 
             
            
            $claimDetail->save();

            $claimID = $claimDetail->id;
            
            $insurednomineeDetails = InsuranceNomineeDetail::where('insurance_claim_details_id',$claimID)->first();
              if($insurednomineeDetails){
                 $nomineeDetails = InsuranceNomineeDetail::find($claimID);
                }else{
                     $nomineeDetails = new InsuranceNomineeDetail;
                     $nomineeDetails->insurance_claim_details_id = $claimID;
                    
                }
          //  print_r($nomineeDetails);die();
            if(!empty($row['54'])){ $nomineeDetails->nominee_name_bank = $row['54']; } 
            if(!empty($row['55'])){ $nomineeDetails->bank_name = $row['55']; } 
            if(!empty($row['56'])){ $nomineeDetails->acc_number = $row['56']; } 
            if(!empty($row['57'])){ $nomineeDetails->ifsc = $row['57']; } 
            if(!empty($row['58'])){ $nomineeDetails->branch_name = $row['58']; } 
            if(!empty($row['59'])){ $nomineeDetails->spdc_bank_name = $row['59']; } 
            if(!empty($row['60'])){ $nomineeDetails->spdc_chk_no = $row['60']; } 
            if(!empty($row['61'])){ $nomineeDetails->courier_name = $row['61']; } 
            if(!empty($row['62'])){ $nomineeDetails->pod_no = $row['62']; } 
            if(!empty($row['63'])){ $nomineeDetails->nominee_number = $row['63']; }
            
            if(!empty($row['64'])){ $nomineeDetails->bo_remarks = $row['64']; } 
            if(!empty($row['65'])){ $nomineeDetails->bo_maker = $row['65']; } 
            if(!empty($row['66'])){ $nomineeDetails->bo_checker = $row['66']; } 

            if(!empty($row['67'])){$nomineeDetails->ack_rec_date = is_numeric($row['67'])? Date::excelToDateTimeObject($row['67'])->format('Y-m-8'): $row['67'];}
            if(!empty($row['68'])){$nomineeDetails->spdc_rec_date = is_numeric($row['68'])? Date::excelToDateTimeObject($row['68'])->format('Y-m-d'): $row['68'];}

            if(!empty($row['69'])){ $nomineeDetails->pkt_no = $row['69']; } 
            
            $nomineeDetails->save();

           /* $checklistDetails = InsuranceChecklist::where('insurance_claim_details_id',$claimDetail->id)->first();
              if($checklistDetails){
                 $chlistDetails = InsuranceChecklist::find($checklistDetails->id);
                }else{
                     $chlistDetails = new InsuranceChecklist;
                     $chlistDetails->insurance_claim_details_id = $claimID;                 
                }
                $chlistDetails->save();*/

        return ;

    }
   
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

   
    public function customValidationMessages()
    {
        return [
            '6.required' => 'policy_number is required',
            '13.required' => 'date_of_death is required',
        ];
    }

    public function onFailure(Failure ...$failures)
    {
        $this->collectedFailures = array_merge($this->collectedFailures, $failures);

        foreach ($failures as $failure) {
            $rowIndex = $failure->row(); // 2, 10, 15, etc.
            $errorMessage = implode(', ', $failure->errors());
            $rowData = $failure->values();

            $this->failedRows[] = [
                'row' => $rowIndex,
                'data' => $rowData,
                'error' => $errorMessage,
            ];
        }
    }

    public function getCollectedFailures(): array
    {
        return $this->collectedFailures;
    }

    public function onError(Throwable $e)
    {
        Log::error("Error during import: " . $e->getMessage());
    }

    protected function ensureMissingIndexesAreNull(array $row, array $indexes): array
{
    foreach ($indexes as $index) {
        if (!array_key_exists($index, $row)) {
            $row[$index] = null;
        }
    }
    return $row;
}


}
