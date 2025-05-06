<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DtrfDocument extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'unique_ref_no', 'region', 'branch_code', 'branch_name',
        'account_creation_date', 'barcode', 'business_category'
    ];
}
