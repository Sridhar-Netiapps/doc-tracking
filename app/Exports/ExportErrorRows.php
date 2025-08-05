<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\InsuranceNomineeDetail;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ExportErrorRows implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    private $Errordata;

    public function __construct($Errordata ) 
    {
        $this->Errordata = $Errordata;
        
    } 

    public function collection()
    {
        $data = $this->Errordata;
        $formattedData = collect();
        
         foreach($data as $key=>$value){
         	
           
            $formattedData->push([
                $value[0],
                $value[1],
                $value[2],
                $value[3],
                $value[4],
                $value[5],
                $value[6],
                is_numeric($value['7'])? Date::excelToDateTimeObject($value['7'])->format('Y-m-d'): $value['7'],
                is_numeric($value['8'])? Date::excelToDateTimeObject($value['8'])->format('Y-m-d'): $value['8'],
                $value[9],
                $value[10],
                $value[11],
                is_numeric($value['12'])? Date::excelToDateTimeObject($value['12'])->format('Y-m-d'): $value['12'],
                is_numeric($value['13'])? Date::excelToDateTimeObject($value['13'])->format('Y-m-d'): $value['13'],
                $value[14],
                $value[15],
                $value[16],
                is_numeric($value['17'])? Date::excelToDateTimeObject($value['17'])->format('Y-m-d'): $value['17'],
                $value[18],
                $value[19],
                $value[20],
                '="'.$value[21].'"',
                $value[22],
                $value[23],     
                $value[24],
               
                is_numeric($value['25'])? Date::excelToDateTimeObject($value['25'])->format('Y-m-d'): $value['25'],
                $value[26],
                is_numeric($value['27'])? Date::excelToDateTimeObject($value['27'])->format('Y-m-d'): $value['27'],
                is_numeric($value['28'])? Date::excelToDateTimeObject($value['28'])->format('Y-m-d'): $value['28'],
                 $value[29],
                $value[30],
                $value[31],
                $value[32],
                $value[33],
                $value[34],
                $value[35],
                $value[36],
                $value[37],
                is_numeric($value['38'])? Date::excelToDateTimeObject($value['38'])->format('Y-m-d'): $value['38'],
			    is_numeric($value['39'])? Date::excelToDateTimeObject($value['39'])->format('Y-m-d'): $value['39'],
                $value[40],
                is_numeric($value['41'])? Date::excelToDateTimeObject($value['41'])->format('Y-m-d'): $value['41'],
                $value[42],
                $value[43],
                $value[44],
                $value[45],
                
                is_numeric($value['46'])? Date::excelToDateTimeObject($value['46'])->format('Y-m-d'): $value['46'],
                is_numeric($value['47'])? Date::excelToDateTimeObject($value['47'])->format('Y-m-d'): $value['47'],
                $value[48],
                $value[49],
                is_numeric($value['50'])? Date::excelToDateTimeObject($value['50'])->format('Y-m-d'): $value['50'], 
                $value[51],
                $value[52],
                $value[53],
                $value[54],
            
                $value[55],
                $value[56],
                $value[57],
                $value[58],
                $value[59],
                $value[60],
                $value[61],
                $value[62],
                $value[63],
                $value[64],
                $value[65],
                $value[66],
                is_numeric($value['67'])? Date::excelToDateTimeObject($value['67'])->format('Y-m-d'): $value['67'],
			    is_numeric($value['68'])? Date::excelToDateTimeObject($value['68'])->format('Y-m-d'): $value['68'],
                $value[69],
               
                
            ]);
         }
         return $formattedData ;
    }

    public function headings(): array

    {

        return [
        "Lead ID",
        "REGION",
        "BRANCH ID-NAME",
        "Partner",	
        "Product" ,
        "Member Code",
        "Policy Number"	,
        "Policy Covered Date",
        "Policy Expired Date",
        "Customer ID",	
        "ACTUAL ID",	
        "DECEASED NAME"	,
        "Date of Birth",
        "DATE OF DEATH",
        "Gender",
        "AGE",
        "DECEASED",
        "DEATH INTIMATION DATE",
        "PLACE OF DEATH",
        "CAUSE OF DEATH",
        "Loan Account ID",
        "Loan Tenure",
        "CLAIM AMT",
        "Nominee Name",
        "RELATIONSHIP",
        "Date of document received",
        "Processed by",
        "Date of submision to partner",
        "Date of re-submision to partner",
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
        "Write off received",
        "Write off status",
        "Handed over to Business Head",
        "Handed over to credit",
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
        "Maker at Branch",	
        "Checker at Branch",
        "Ack Received Date",
        "SPDC Received Date",
        "Packet Number",
       
        ];

    }
}
