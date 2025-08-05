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
       
       $insurancedetaisl = InsuranceClaimDetail::where('utrn',$row['0'])->first();
       $exp_date = '';
       $myage = '';

	       $errors = [];
	       if (empty($row[1]) ) {
		        $errors[] = 'Region is required (Col B)';
		    }

		    if (!empty($row[1]) && is_numeric($row[1]) ) {
		        $errors[] = 'Region cannot be a integer value (Col B)';
		    }

		    if (empty($row[2])) {
		        $errors[] = 'Branch ID-Name is required (Col C)';
		        $branch = explode('-',$row[2]) ;
		    }
		    if (!empty($row[2])) {
		        $branch = explode('-',$row[2]) ;
		        if(!is_numeric($branch[0])){
		        	$errors[] = 'Branch ID should be Numeric. Ex:1100-Koramangala (Col C)';
		        }
		        if(is_numeric($branch[0]) && strlen($branch[0]) !=4){
		        	$errors[] = 'Branch ID should be 4 digits only. Ex:1100-Koramangala (Col C)';
		        }
		    }

		    if (empty($row[3])) {
		        $errors[] = 'Partner is required (Col D)';
		    }

		    if (empty($row[4])) {
		        $errors[] = 'Product is required (Col E)';
		    }

		    if (empty($row[6])) {
		        $errors[] = 'Policy Number is required (Col G)';
		    }

		    if (empty($row[13])) {
		        $errors[] = 'Date of Death is required (Col M)';
		    }

		    if (!empty($row[57]) && strlen($row[57]) != 11) {
		        $errors[] = 'IFSC should be of 11 characters (Col BF)';
		    }

		    if (!empty($row[63]) && strlen($row[63]) != 10) {
		        $errors[] = 'Nominee Contact Number should be 10 digits (Col BL)';

		        if(!is_numeric($row[63])){
		        	$errors[] = 'Nominee Contact Number should contain only Numbers (Col BL)';
		        }
		    }

		    if (!empty($row[7]) && !empty($row[21])) {
		    	$coverd=Date::excelToDateTimeObject($row['7'])->format('Y-m-d');
		        $exp_date = date('Y-m-d',strtotime('+'.$row[21].'months', strtotime($coverd)));
		    }

		    if (!empty($row[12])) {
		    	$dofb=Date::excelToDateTimeObject($row['12'])->format('Y-m-d');
		        $now = now();
		        $interval  = $now->diff($dofb);
		        $myage = ($interval->format('%y years %m months'));
		    }

		    if (!empty($row[17]) && !empty($row[25])) {
                $intDate = strtotime(Date::excelToDateTimeObject($row['17'])->format('Y-m-d'));
                $docRecDate = strtotime(Date::excelToDateTimeObject($row['25'])->format('Y-m-d'));
                if($docRecDate < $intDate){
                	$errors[] = 'Document Received Date cannot be before Date of Intimation (Col Z)';
                }
		    } 

		    if (!empty($row[27]) && !empty($row[28])) {
                $subDate = strtotime(Date::excelToDateTimeObject($row['27'])->format('Y-m-d'));
                $resubDate = strtotime(Date::excelToDateTimeObject($row['28'])->format('Y-m-d'));
                if($resubDate < $subDate){
                	$errors[] = 'Date of re-submision to partner cannot be before Date of submision to partner (Col AC)';
                }
		    } 	
		   

		    if (!empty($errors)) {
		        $failure = new Failure(
		            $this->rowCount,   // current row number
		            'row',             // can be 'row' or a specific column
		            $errors,           // array of error messages
		            $row               // raw row data
		        );
		        $this->onFailure($failure);
		        return null; // Skip processing
		    }

	    //print_r($failure);die();

		      if($insurancedetaisl){
			         $claimDetail = InsuranceClaimDetail::find($insurancedetaisl->id);
		             $claimDetail->latest_editor = Auth::user()->employee_id; 
			         $this->updatedCount++;
			   }else{
			         $claimDetail = new InsuranceClaimDetail;
			          $utrn = 'INS_CLM'.rand('000000','999999');
			          $claimDetail->utrn = $utrn;
			          $claimDetail->ho_employee_id = Auth::user()->employee_id; 
			          $this->insertedCount++;
			    }


	      
            if(!empty($row['1'])){ $claimDetail->region = $row['1']; } 
			if(!empty($row['2'])){ $claimDetail->branch = $row['2']; } 
			if(!empty($row['3'])){ $claimDetail->partner = $row['3']; } 
			if(!empty($row['4'])){ $claimDetail->product = $row['4']; } 
			if(!empty($row['5'])){ $claimDetail->mp_no = $row['5']; } 
			if(!empty($row['6'])){ $claimDetail->policy_number = $row['6']; } 
			if(!empty($row['7'])){ $claimDetail->policy_covered_date = is_numeric($row['7'])? Date::excelToDateTimeObject($row['7'])->format('Y-m-d'): $row['7'];} 
			if(!empty($row['8'])){$claimDetail->policy_expiry_date = $exp_date ?? (is_numeric($row['8'])? Date::excelToDateTimeObject($row['8'])->format('Y-m-d'): $row['8']);}
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
