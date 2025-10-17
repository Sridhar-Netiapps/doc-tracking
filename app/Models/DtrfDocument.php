<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class DtrfDocument extends Model
{
    use HasFactory,SoftDeletes;
    
    protected $fillable = [
        'unique_ref_no', 'region', 'branch_code', 'branch_name',
        'account_creation_date', 'barcode', 'business_category', 'status'
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
                    ->where('document_type', 'DtrfDocument')
                    ->whereIn('current_status', [5,6,7]);
    }
}
