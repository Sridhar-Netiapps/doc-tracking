<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanDocument extends Model
{
    use HasFactory;
    protected $fillable = [
        'unique_ref_no', 'region', 'branch_code', 'branch_name', 'cif_id', 'account_number',
        'loan_cycle', 'customer_name', 'account_creation_date', 'channel', 'barcode',
        'loan_disbursement_type', 'business_category','status'
    ];
}
