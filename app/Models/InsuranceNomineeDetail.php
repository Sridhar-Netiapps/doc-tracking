<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InsuranceNomineeDetail extends Model
{
    protected $fillable=[
    	'insurance_claim_details_id',
    	'nominee_name_bank',
    	'bank_name',
    	'acc_number',
    	'ifsc',
    	'branch_name',
    	'spdc_bank_name',
    	'spdc_chk_no',
    	'courier_name',
    	'pod_no',
    	'cheq_sent_date',
    	'bo_remarks',
    	'bo_maker',
    	'bo_checker',
    	'bo_employee_id',
    	'latest_editor'
    ];

}
