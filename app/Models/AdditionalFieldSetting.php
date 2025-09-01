<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdditionalFieldSetting extends Model
{
    protected $fillable = [
        'field_name',
        'field_type',
        'allowed_chars',
        'module',
        'creator'
    ];
    
     public function Additional_fileds(){
    	return $this->hasMany(AdditionalField::class);
    }

}
