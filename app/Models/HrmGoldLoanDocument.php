<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HrmGoldLoanDocument extends Model
{
    use HasFactory;
    protected $fillable = [
        'unique_ref_no', 'region', 'branch_code', 'branch_name', 'cif_id',
        'account_number', 'customer_name', 'account_creation_date','loan_amount',
        'channel', 'barcode', 'business_category'
    ];
}
