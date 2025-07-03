<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InsuranceCauseOfDeath extends Model
{
    protected $fillable=[
    	'cause',
    	'description'
    ];
}
