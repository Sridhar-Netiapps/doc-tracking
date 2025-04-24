<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountOpeningDocument extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'unique_ref_no', 'region', 'branch_code', 'branch_name', 'cif_id',
        'account_number', 'customer_name', 'account_creation_date', 'scheme',
        'channel', 'barcode', 'type_of_account_opening', 'business_category'
    ];
}
