<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentHistory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'document_id',
        'document_type',
        'previous_status',
        'current_status',
        'remarks',
        'created_by',
        'created_at',
    ];
}
