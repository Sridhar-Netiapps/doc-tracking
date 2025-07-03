<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\InsuranceNomineeDetail;


class ExportInsuranceLeads implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    private $data;

    public function __construct($data ) 
    {
        $this->data = $data;
        
    } 

    public function collection()
    {
         $data = $this->data;
        $formattedData = collect();
        
         foreach($data as $key=>$value){
            $nominee = InsuranceNomineeDetail::where('insurance_claim_details_id',$value->id)->first();
           
            $formattedData->push([
                $value->id,
                $value->utrn,
                $value->region,
                $value->branch,
                $value->partner,
                $value->product,
                $value->policy_number,
                $value->cust_id,
                $value->actual_id,
                $value->deceased_name,
                $value->mp_no,
                $value->policy_covered_date,
                $value->loan_tenure,
                $value->policy_expiry_date,
                $value->date_of_death,
                $value->gender,
                $value->deceased,
                $value->intimation_date,
                $value->age,
                $value->place_of_death,
                $value->cause_of_death,
                $value->load_acc_id,
                $value->claim_amount,
                $value->dob,
                $value->cliam_status,
                $value->cas_status,
                $value->nominee_name,
                $value->relationship,
                $value->nominee_number,
                $nominee->nominee_name_bank,
                $nominee->bank_name,
                $nominee->acc_number,
                $nominee->ifsc,
                $nominee->branch_name,
                $value->loan_outstanding,
                $value->payable_to_nominee,
                $nominee->spdc_bank_name,
                $nominee->spdc_chk_no,
                $nominee->courier_name,
                $nominee->pod_no,
                $nominee->cheq_sent_date,
                $value->ack_rec_date,
                $value->pkt_no,
                $value->rl_status,
                $value->processed_by,
                $value->ho_remark,
                $value->doc_rec_date,
                $value->submit_to_partner_date,
                $value->ho_remark2,
                $value->re_submit_to_partner_date,
                $value->settlement_date,
                $value->neft_rejection_date,
                $value->neft_rejection_reason,
                $value->final_settlement_date,
                $value->recovery_status,
                $value->bounced_chq_no,
                $value->bounced_chq_date,
                $value->bounced_chq_reason,
                $value->write_off_rec,
                $value->write_off_status,
                $value->handed_to_bh,
                $value->handed_to_credit,
                $nominee->bo_remarks,
                $nominee->bo_maker,
                $nominee->bo_checker,
                
            ]);
         }
         return $formattedData ;
    }

    public function headings(): array

    {

        return ["Reference ID ","Lead ID","REGION","BRANCH ID-NAME",	"Partner",	"Product"	,"Policy Number"	,"Customer ID",	"ACTUAL ID",	"DECEASED NAME"	,"MP NO",	"POLICY COVERED",	"Loan Tenure",	"Policy Expired Date",	"DATE OF DEATH",	"Gender",	"DECEASED",	"DEATH INTIMATION DATE"	,"AGE",	"PLACE OF DEATH",	"CAUSE OF DEATH",	"Loan Account ID",	"CLAIM AMT",	"Date of Birth",	"Claim Status",	"CAS Status",	"Nominee Name",	"RELATIONSHIP",	"Nominee Contact No",	"Nominee Name as per Bank Records",	"Name of the Bank",	"Bank A/c Number",	"IFSC Code"	,"Bank Branch Name",	"Loan Outstanding Amt",	"Payable to Nominee",	"SPDC-Bank Name",	"SPDC-Chq Number",	"Courier Name",	"POD Number",	"CHEQUE SENT DATE",	"ACKNOWLEDGEMENT RECEIVED DATE",	"Packet Number",	"SPDC/RL Status",	"Processed by"	,"HO Remarks",	"Date of document received",	"Date of submision to partner",	"Remarks",	"Date of re-submision to partner",	"Date of settlement",	"NEFT Rejection Date",	"NEFT Reason For Rejection",	"Final Settlement Date"	,"Recovery Status",	"Bounced CHQ No",	"CHQ Bounced Date",	"CHQ Bounced reason",	"Write off received",	"Write off status",	"Handed over to Business Head",	"Handed over to credit",	"Branch Remarks",	"Maker at Branch",	"Checker at Branch"];

    }
}
