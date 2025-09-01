<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdditionalField extends Model
{
    protected $fillable=[
    	'insurance_claim_details_id',
    	'additional_field_settings_id',
    	'param_name',
    	'param_value',
    	'creator'
    ];

    public function settingData(){
    	return $this->belongsTo(AdditionalFieldSetting::class,'additional_field_settings_id','id');
    }


}
