<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InsuranceDocument extends Model
{
    protected $fillable=[
    	'insurance_claim_details_id',
    	'original_name',
    	'stored_name',
    	'filepath',
    	'status',
    	'creator',
    	'updator'
    ];

    
}
