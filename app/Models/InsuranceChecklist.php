<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InsuranceChecklist extends Model
{
    protected $fillable= [
            'insurance_claim_details_id',
            'cust_id',
            'branch_id',
            'sent_date',
            'deceased_name',
            'name',
            'name_mismatch',
            'age',
            'age_mismatch',
            'customer_id',
            'dod',
            'is_mlc',
            'fir_attached',
            'death_certificate',
            'valid_certificate',
            'doc_bajaj',
            'doc_death',
            'doc_fir',
            'doc_proof',
            'doc_closure_request',
            'doc_ecs',  
            'docs_readable',
            'nominee_name',
            'acc_no',
            'bank_name',
            'micr',
            'ifsc',
            'branch',
            'bo_maker_emp',
            'bo_maker_name',
            'bo_maker_sign',
            'bo_maker_date',
            'bo_checker_emp',
            'bo_checker_name',
            'bo_checker_sign',
            'bo_checker_date',
            'ho_maker_emp',
            'ho_maker_name',
            'ho_checker_emp',
            'ho_checker_name',
        ];
}
