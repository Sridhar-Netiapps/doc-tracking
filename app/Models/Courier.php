<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Courier extends Model
{
    use HasFactory;
    protected $table = 'couriers';

    protected $fillable = [
        'courier_id', 'name', 'number', 'address', 'status', 'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
    ];
}

