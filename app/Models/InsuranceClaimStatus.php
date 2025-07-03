<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InsuranceClaimStatus extends Model
{
    protected $fillable=[
    	'claim_status',
    	'description'
    ];
}
