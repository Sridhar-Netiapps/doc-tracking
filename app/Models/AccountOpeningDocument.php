<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class AccountOpeningDocument extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'unique_ref_no', 'region', 'branch_code', 'branch_name', 'cif_id',
        'account_number', 'customer_name', 'account_creation_date', 'scheme',
        'channel', 'pgk_no', 'barcode', 'type_of_account_opening', 'business_category'
    ];

    public function statusName()
    {
        return $this->belongsTo(ProcessStatus::class, 'status');
    }
    public function modifier()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function dispatch()
    {
        return $this->belongsTo(CourierDispatch::class,'dispatch_id');
    }
}
