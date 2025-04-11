<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'unique_ref_no',
        'region',
        'branch_code',
        'branch_name',
        'cif_id',
        'account_number',
        'customer_name',
        'account_creation_date',
        'channel',
        'business_category',
        'barcode',
        'type_of_account',
        'scheme',
        'loan_cycle',
    ];
}
