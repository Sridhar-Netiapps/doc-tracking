<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourierDispatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'awb_pod',
        'courier_id',
        'courier_name',
        'loan_ids',
        'goldloan_ids',
        'dtrf_ids',
        'aof_ids',
        'dispatch_date',
        'dispatched_by',
        'status',
        'verified_by',
        'updated_by',
        'deleted_by'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'dispatched_by');
    }
    public function modifier()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}