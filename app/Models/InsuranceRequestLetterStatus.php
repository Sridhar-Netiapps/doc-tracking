<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InsuranceRequestLetterStatus extends Model
{
    protected $fillable=[
    	'rl_status',
    	'description'
    ];
}
