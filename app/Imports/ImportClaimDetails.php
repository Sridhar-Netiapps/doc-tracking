<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\InsuranceClaimDetail;
use App\Models\InsuranceNomineeDetail;
use App\Models\InsuranceChecklist;
use Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ImportClaimDetails implements ToModel, WithStartRow
{
    /**s
    * @param Collection $collection
    */

    public $rowCount = 0;
    public $insertedCount = 0;
    public $updatedCount = 0;


     public function startRow(): int
    {
        return 2;
    } 
    
    public function model(array $row)
    {
       ++$this->rowCount;

       
       $insurancedetaisl = InsuranceClaimDetail::where('utrn',$row['0'])->first();

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
			if(!empty($row['5'])){ $claimDetail->policy_number = $row['5']; } 
			if(!empty($row['6'])){ $claimDetail->cust_id = $row['6']; } 
			if(!empty($row['7'])){ $claimDetail->actual_id = $row['7']; } 
			if(!empty($row['8'])){ $claimDetail->deceased_name = $row['8']; } 
			if(!empty($row['9'])){ $claimDetail->mp_no = $row['9']; } 
			//if(!empty($row['10'])){ $claimDetail->policy_covered_date = $row['10']; } 
			if(!empty($row['10'])){$claimDetail->policy_covered_date = is_numeric($row['10'])? Date::excelToDateTimeObject($row['10'])->format('Y-m-d'): $row['10'];}

			if(!empty($row['11'])){ $claimDetail->loan_tenure = $row['11']; } 
			if(!empty($row['12'])){ $claimDetail->policy_expiry_date = $row['12']; } 
			//if(!empty($row['13'])){ $claimDetail->date_of_death = $row['13']; } 
			if(!empty($row['12'])){$claimDetail->policy_expiry_date = is_numeric($row['12'])? Date::excelToDateTimeObject($row['12'])->format('Y-m-d'): $row['12'];}

			if(!empty($row['13'])){$claimDetail->date_of_death = is_numeric($row['13'])? Date::excelToDateTimeObject($row['13'])->format('Y-m-d'): $row['13'];}

			if(!empty($row['14'])){ $claimDetail->gender = $row['14']; } 
			if(!empty($row['15'])){ $claimDetail->deceased = $row['15']; } 
			//if(!empty($row['16'])){ $claimDetail->intimation_date = $row['16']; } 
			if(!empty($row['16'])){$claimDetail->intimation_date = is_numeric($row['16'])? Date::excelToDateTimeObject($row['16'])->format('Y-m-d'): $row['16'];}

			if(!empty($row['17'])){ $claimDetail->age = $row['17']; } 
			if(!empty($row['18'])){ $claimDetail->place_of_death = $row['18']; } 
			if(!empty($row['19'])){ $claimDetail->cause_of_death = $row['19']; } 
			if(!empty($row['20'])){ $claimDetail->load_acc_id = $row['20']; } 
			if(!empty($row['21'])){ $claimDetail->claim_amount = $row['21']; } 
			//if(!empty($row['22'])){ $claimDetail->dob = $row['22']; } 
			if(!empty($row['22'])){$claimDetail->dob = is_numeric($row['22'])? Date::excelToDateTimeObject($row['22'])->format('Y-m-d'): $row['22'];}

			if(!empty($row['23'])){ $claimDetail->cliam_status = $row['23']; } 
			if(!empty($row['24'])){ $claimDetail->cas_status = $row['24']; } 
			if(!empty($row['25'])){ $claimDetail->nominee_name = $row['25']; } 
			if(!empty($row['26'])){ $claimDetail->relationship = $row['26'];} 
			if(!empty($row['27'])){ $claimDetail->nominee_number = $row['27']; } 
			if(!empty($row['33'])){ $claimDetail->loan_outstanding = $row['33']; } 
			if(!empty($row['34'])){ $claimDetail->payable_to_nominee = $row['34']; } 
			//if(!empty($row['40'])){ $claimDetail->ack_rec_date = $row['40']; } 
			if(!empty($row['40'])){$claimDetail->ack_rec_date = is_numeric($row['40'])? Date::excelToDateTimeObject($row['40'])->format('Y-m-d'): $row['40'];}

			if(!empty($row['41'])){ $claimDetail->pkt_no = $row['41']; } 
			if(!empty($row['42'])){ $claimDetail->rl_status = $row['42']; } 
			if(!empty($row['43'])){ $claimDetail->processed_by = $row['43']; } 
			if(!empty($row['44'])){ $claimDetail->ho_remark = $row['44']; } 
			//if(!empty($row['45'])){ $claimDetail->doc_rec_date = $row['45']; } 
			if(!empty($row['45'])){$claimDetail->doc_rec_date = is_numeric($row['45'])? Date::excelToDateTimeObject($row['45'])->format('Y-m-d'): $row['45'];}
			//if(!empty($row['46'])){ $claimDetail->submit_to_partner_date = $row['46']; }
			if(!empty($row['46'])){$claimDetail->submit_to_partner_date = is_numeric($row['46'])? Date::excelToDateTimeObject($row['46'])->format('Y-m-d'): $row['46'];}

			if(!empty($row['47'])){ $claimDetail->ho_remark2 = $row['47']; } 
			//if(!empty($row['48'])){ $claimDetail->re_submit_to_partner_date = $row['48']; } 
			if(!empty($row['48'])){$claimDetail->re_submit_to_partner_date = is_numeric($row['48'])? Date::excelToDateTimeObject($row['48'])->format('Y-m-d'): $row['48'];}

			//if(!empty($row['49'])){ $claimDetail->settlement_date = $row['49']; } 
			if(!empty($row['49'])){$claimDetail->settlement_date = is_numeric($row['49'])? Date::excelToDateTimeObject($row['49'])->format('Y-m-d'): $row['49'];}
			//if(!empty($row['50'])){ $claimDetail->neft_rejection_date = $row['50']; } 
			
			if(!empty($row['50'])){$claimDetail->neft_rejection_date = is_numeric($row['50'])? Date::excelToDateTimeObject($row['50'])->format('Y-m-d'): $row['50'];}
			if(!empty($row['51'])){ $claimDetail->neft_rejection_reason = $row['51']; } 
			//if(!empty($row['52'])){ $claimDetail->final_settlement_date = $row['52']; } 
			if(!empty($row['52'])){$claimDetail->final_settlement_date = is_numeric($row['52'])? Date::excelToDateTimeObject($row['52'])->format('Y-m-d'): $row['52'];}

			if(!empty($row['53'])){ $claimDetail->recovery_status = $row['53']; } 
			if(!empty($row['54'])){ $claimDetail->bounced_chq_no = $row['54']; } 
			//if(!empty($row['55'])){ $claimDetail->bounced_chq_date = $row['55']; } 
			if(!empty($row['55'])){$claimDetail->bounced_chq_date = is_numeric($row['55'])? Date::excelToDateTimeObject($row['55'])->format('Y-m-d'): $row['55'];}

			if(!empty($row['56'])){ $claimDetail->bounced_chq_reason = $row['56']; } 
			if(!empty($row['57'])){ $claimDetail->write_off_rec = $row['57']; } 
			if(!empty($row['58'])){ $claimDetail->write_off_status = $row['58']; } 
			if(!empty($row['59'])){ $claimDetail->handed_to_bh = $row['59']; } 
			if(!empty($row['60'])){ $claimDetail->handed_to_credit = $row['60']; } 
            
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
            if(!empty($row['28'])){ $nomineeDetails->nominee_name_bank = $row['28']; } 
            if(!empty($row['29'])){ $nomineeDetails->bank_name = $row['29']; } 
            if(!empty($row['30'])){ $nomineeDetails->acc_number = $row['30']; } 
            if(!empty($row['31'])){ $nomineeDetails->ifsc = $row['31']; } 
            if(!empty($row['32'])){ $nomineeDetails->branch_name = $row['32']; } 
            if(!empty($row['35'])){ $nomineeDetails->spdc_bank_name = $row['35']; } 
            if(!empty($row['36'])){ $nomineeDetails->spdc_chk_no = $row['36']; } 
            if(!empty($row['37'])){ $nomineeDetails->courier_name = $row['37']; } 
            if(!empty($row['38'])){ $nomineeDetails->pod_no = $row['38']; } 
            //if(!empty($row['39'])){ $nomineeDetails->cheq_sent_date = $row['39']; } 
            if(!empty($row['39'])){$claimDetail->cheq_sent_date = is_numeric($row['39'])? Date::excelToDateTimeObject($row['39'])->format('Y-m-d'): $row['39'];}

			if(!empty($row['61'])){ $nomineeDetails->bo_remarks = $row['61']; } 
			if(!empty($row['62'])){ $nomineeDetails->bo_maker = $row['62']; } 
			if(!empty($row['63'])){ $nomineeDetails->bo_checker = $row['63']; } 
            
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
}
