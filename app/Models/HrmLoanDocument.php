<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HrmLoanDocument extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'unique_ref_no', 'region', 'branch_code', 'branch_name', 'cif_id', 'account_number',
        'loan_cycle', 'customer_name', 'account_creation_date', 'channel', 'barcode','loan_amount',
        'loan_disbursement_type', 'business_category','status'
    ];
}
