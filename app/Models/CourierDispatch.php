<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class CourierDispatch extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'awb_pod',
        'courier_id',
        'mmrp_barcode',
        'branch_code',
        'region',
        'courier_name',
        'loan_ids',
        'goldloan_ids',
        'dtrf_ids',
        'aof_ids',
        'dispatch_date',
        'created_by',
        'status',
        'verified_by',
        'updated_by',
        'deleted_by'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function modifier()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function statusName()
    {
        return $this->belongsTo(ProcessStatus::class, 'status');
    }

    public function courierName()
    {
        return $this->belongsTo(Courier::class, 'courier_name');
    }
}