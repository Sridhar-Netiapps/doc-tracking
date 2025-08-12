<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class LoanDocument extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'unique_ref_no', 'region', 'branch_code', 'branch_name', 'cif_id', 'account_number',
        'loan_cycle', 'customer_name', 'account_creation_date', 'channel', 'glow_application_id', 'loan_amount', 'barcode',
        'loan_disbursement_type', 'business_category','status'
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
    public function history()
    {
        return $this->belongsTo(DocumentHistory::class,'document_id');
    }
    public function getReceivedDetails()
    {
        return $this->hasOne(DocumentHistory::class, 'document_id')
                    ->where('document_type', 'LoanDocument')
                    ->whereIn('current_status', [5,7]);
    }
}
