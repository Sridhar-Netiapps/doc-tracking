<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Volunteer;


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
         return $data ;
    }

    public function headings(): array

    {

        return ["Reference ID ","Lead ID","REGION","BRANCH ID-NAME",	"Partner",	"Product"	,"Policy Number"	,"Customer ID",	"ACTUAL ID",	"DECEASED NAME"	,"MP NO",	"POLICY COVERED",	"Loan Tenure",	"Policy Expired Date",	"DATE OF DEATH",	"Gender",	"DECEASED",	"DEATH INTIMATION DATE"	,"AGE",	"PLACE OF DEATH",	"CAUSE OF DEATH",	"Loan Account ID",	"CLAIM AMT",	"Date of Birth",	"Claim Status",	"CAS Status",	"Nominee Name",	"RELATIONSHIP",	"Nominee Contact No",	"Nominee Name as per Bank Records",	"Name of the Bank",	"Bank A/c Number",	"IFSC Code"	,"Bank Branch Name",	"Loan Outstanding Amt",	"Payable to Nominee",	"SPDC-Bank Name",	"SPDC-Chq Number",	"Courier Name",	"POD Number",	"CHEQUE SENT DATE",	"ACKNOWLEDGEMENT RECEIVED DATE",	"Packet Number",	"SPDC/RL Status",	"Processed by"	,"HO Remarks",	"Date of document received",	"Date of submision to partner",	"Remarks",	"Date of re-submision to partner",	"Date of settlement",	"NEFT Rejection Date",	"NEFT Reason For Rejection",	"Final Settlement Date"	,"Recovery Status",	"Bounced CHQ No",	"CHQ Bounced Date",	"CHQ Bounced reason",	"Write off received",	"Write off status",	"Handed over to Business Head",	"Handed over to credit",	"Branch Remarks",	"Maker at Branch",	"Checker at Branch"];

    }
}
