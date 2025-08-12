<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\InsuranceNomineeDetail;


class ExportAuditLogs implements FromCollection,WithHeadings
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
           
            $formattedData->push([
            	date('d,M Y H:i:s',strtotime($value->created_at)),
            	$value->operation,
            	$value->note,
            	$value->user->first_name.''.$value->user->middle_name.''.$value->user->last_name,
            	$value->link 
            ]);
        }

        return $formattedData ;
    }    


    public function headings(): array
    {
        return [ 'Date','operation','Action','User Name','Employee ID','Link'];
    }    

}
