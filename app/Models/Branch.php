<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'name',
        'code',
        'region_id',
        'region_name',
        'business_type',
        'rbi_classification',
        'branch_office_type',
        'underbanked_district',
        'operational_date',
        'population_tier',
        'population_group',
        'address_part1',
        'address_part2',
        'address_part3',
        'pincode',
        'city',
        'district_id',
        'state_id',
        'latitude',
        'longitude',
        'post_office',
        'micr',
        'ifsc',
        'opening_fy',
        'branch_type',
        'old_name',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
    ];
}
