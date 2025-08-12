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

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function modifier()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function oldStatus()
    {
        return $this->belongsTo(ProcessStatus::class, 'previous_status');
    }

    public function newStatus()
    {
        return $this->belongsTo(ProcessStatus::class, 'current_status');
    }

    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id');
    }
}
