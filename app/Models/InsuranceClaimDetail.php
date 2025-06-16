<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InsuranceClaimDetail extends Model
{
    protected $fillable=[
    	'utrn',
        'region',
    	'branch',
    	'partner',
    	'product',
    	'policy_number',
    	'cust_id',
    	'actual_id',
    	'deceased_name',
    	'mp_no',
    	'policy_covered_date',
    	'loan_tenure',
    	'policy_expiry_date',
    	'date_of_death',
    	'gender',
    	'deceased',
    	'intimation_date',
    	'age',
    	'place_of_death',
    	'cause_of_death',
    	'load_acc_id',
    	'claim_amount',
    	'dob',
    	'cliam_status',
    	'cas_status',
    	'nominee_name',
    	'relationship',
    	'nominee_number',
    	'loan_outstanding',
    	'payable_to_nominee',
    	'ack_rec_date',
    	'pkt_no',
    	'rl_status',
    	'processed_by',
    	'ho_remark',
    	'doc_rec_date',
    	'submit_to_partner_date',
    	'ho_remark2',
    	're_submit_to_partner_date',
    	'settlement_date',
    	'neft_rejection_date',
    	'neft_rejection_reason',
    	'final_settlement_date',
    	'recovery_status',
    	'bounced_chq_no',
    	'bounced_chq_date',
    	'bounced_chq_reason',
    	'write_off_rec',
    	'write_off_status',
    	'handed_to_bh',
    	'handed_to_credit',
    	'ho_employee_id',
    	'latest_editor'
    ];

    public function products(){
        return $this->belongsTo(InsuranceProduct::class,'product','id');
    }

    public function partners(){
        return $this->belongsTo(InsurancePartner::class,'partner','id');
    }

    public function claim_stat(){
        return $this->belongsTo(InsuranceClaimStatus::class,'cliam_status','id');
    }

    public function deathCause(){
        return $this->belongsTo(InsuranceCauseOfDeath::class,'cause_of_death','id');
    }

    public function rlStatus(){
        return $this->belongsTo(InsuranceRequestLetterStatus::class,'rl_status','id');
    }
}
