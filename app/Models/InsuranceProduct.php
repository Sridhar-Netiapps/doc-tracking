<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InsuranceProduct extends Model
{
    protected $fillable=[
    	'product',
    	'partner_id',
    	'type',
    	'folder_name',
    	'description'
    ];
}
