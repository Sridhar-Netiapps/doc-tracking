<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\AdditionalField;


class ExportInsuranceLeads implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    private $data;

    public function __construct($data ,$additionl_fileds) 
    {
        $this->data = $data;
        $this->additionl_fileds = $additionl_fileds;
        
    } 

    public function collection()
    {
         $data = $this->data;
        $formattedData = collect();

        $fields = $this->additionl_fileds;
        /*$dynamicfielValues =array();
        foreach ($fields as $key => $value) {
           $dynamicfielids[] = $value->id;
        }*/
        
         foreach($data as $key=>$value){

            
            $formattedData->push([
                date('d-m-Y',strtotime($value->created_at)),
                date('d-m-Y',strtotime($value->updated_at)),    
                $value->id,
                $value->utrn,
                $value->region,
                $value->branch,
                $value->partner,
                $value->product,
                $value->mp_no,
                $value->policy_number,
                ($value->policy_covered_date !='') ? date('d-m-Y',strtotime($value->policy_covered_date)) : '',
                ($value->policy_expiry_date !='') ? date('d-m-Y',strtotime($value->policy_expiry_date)) : '',
                $value->cust_id,
                $value->actual_id,
                $value->deceased_name,
                ($value->dob !='')? date('d-m-Y',strtotime($value->dob)) : '',
                ($value->date_of_death !='') ? date('d-m-Y',strtotime($value->date_of_death)) : '',
                $value->gender,
                $value->age,
                $value->deceased,
                ($value->intimation_date !='') ? date('d-m-Y',strtotime($value->intimation_date)) : '',
                $value->place_of_death,
                $value->cause_of_death,
                //$value->load_acc_id,
                '="'.$value->load_acc_id.'"',
                $value->loan_tenure,
                $value->claim_amount,        
                $value->nominee_name,
                $value->relationship,
                ($value->doc_rec_date!='') ? date('d-m-Y',strtotime($value->doc_rec_date)) : '',
                $value->processed_by,
                ($value->submit_to_partner_date !='') ? date('d-m-Y',strtotime($value->submit_to_partner_date)) : '',
                ($value->re_submit_to_partner_date !='') ? date('d-m-Y',strtotime($value->re_submit_to_partner_date)) : '',
                $value->ho_remark,
                $value->ho_remark2,
                $value->cliam_status,
                $value->cas_status,
                $value->rl_status,
                $value->notification_number,
                $value->loan_amount,
                $value->loan_outstanding,
                $value->payable_to_nominee,
                ($value->settlement_date!='') ? date('d-m-Y',strtotime($value->settlement_date)) : '',
                ($value->neft_rejection_date !='') ? date('d-m-Y',strtotime($value->neft_rejection_date)) : '',
                $value->neft_rejection_reason,
                ($value->final_settlement_date!='') ? date('d-m-Y',strtotime($value->final_settlement_date)) : '',
                $value->utrn_mph,
                $value->utrn_nominee,
                $value->recovery_status,
                $value->bounced_chq_no,
                ($value->chq_deposit_date !='') ? date('d-m-Y',strtotime($value->chq_deposit_date)) : '',
                ($value->bounced_chq_date !='') ? date('d-m-Y',strtotime($value->bounced_chq_date)) : '',
                $value->bounced_chq_reason,
                $value->recovered_amount,
                ($value->write_off_rec !='') ? date('d-m-Y',strtotime($value->write_off_rec)):'',
                $value->write_off_status,
                $value->handed_to_bh,
                $value->handed_to_credit,
            
                $value->nominee->nominee_name_bank,
                $value->nominee->bank_name,
                '="'.$value->nominee->acc_number.'"',
                $value->nominee->ifsc,
                $value->nominee->branch_name,
                $value->nominee->spdc_bank_name,
                $value->nominee->spdc_chk_no,
                $value->nominee->courier_name,
                $value->nominee->pod_no,
                $value->nominee->nominee_number,
                $value->nominee->bo_remarks,
                ($value->nominee->ack_rec_date !='') ? date('d-m-Y',strtotime($value->nominee->ack_rec_date)) : '' ,
                ($value->nominee->ack_rec_date !='') ? date('d-m-Y',strtotime($value->nominee->spdc_rec_date)) : '', 
                $value->nominee->pkt_no,
                $value->nominee->bo_maker,
                $value->nominee->bo_checker,

                $value->ho_employee_id,
                $value->latest_editor,
                
            ]);
         }

          

         return $formattedData ;
    }

    public function headings(): array

    {

        $fields = $this->additionl_fileds;
        $dynamicfielNames =array();
        foreach ($fields as $key => $value) {
           $dynamicfielNames[] = $value->field_name;
        }

        $fields = array_merge([
        'Creation Date',
        'Last Modified Date',
        "Reference ID ",
        "Lead ID",
        "REgion",
        "Branch ID - Name",
        "Partner",  
        "Product" ,
        "Member Code",
        "Policy Number" ,
        "Policy Covered Date",
        "Policy Expired Date",
        "Customer ID",  
        "Actual ID",    
        "Deceased Name" ,
        "Date of Birth",
        "Date of Death",
        "Gender",
        "Age",
        "Deceased",
        "Death Intimation Date",
        "Place of Death",
        "Cause of Death",
        "Loan Account ID",
        "Loan Tenure",
        "Claim Amount",
        "Nominee Name",
        "Relationship",
        "Date of Document Received",
        "Processed by",
        "Date of Submision to Partner",
        "Date of Re-submision to partner",
        "HO Remarks",
        "Remarks",
        "Claim Status",
        "CAS Status",
        "SPDC/RL Status",
        "Notificatiion No",
        "Loan Amount",
        "Loan Outstanding Amt",
        "Payable to Nominee",
        "Date of settlement",
        "NEFT Rejection Date",
        "NEFT Reason For Rejection",
        "Final Settlement Date",
        "UTRN of MPH",
        "UTRN of Nominee",
        "Recovery Status",
        "Bounced SPDC No",
        "SPDC Doposit Date",
        "SPDC Bounced Date",
        "SPDC Bounced reason",
        "Recovered Amount",
        "Write off Received",
        "Write off Status",
        "Handed over to Business Head",
        "Handed over to Credit",
        "Nominee Name as per Bank Records",
        "Name of the Bank",
        "Bank A/c Number",
        "IFSC Code",
        "Bank Branch Name",
        "SPDC-Bank Name",
        "SPDC-Chq Number",
        "Courier Name",
        "POD Number",
        "Nominee Contact Number",
        "Branch Remarks",
        "Ack Received Date",
        "SPDC Received Date",
        "Packet Number",
        "Maker at Branch",  
        "Checker at Branch",
        "Created By",
        "Modified By"],
       // $dynamicfielNames
    );


    
        return $fields;

    }
}
