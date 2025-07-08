<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use App\Models\DocumentHistory;
use Illuminate\Support\Facades\Auth;

class DocumentObserver
{
    public function creating(Model $model)
    {
        if ($model->isDirty('status')) {
            DocumentHistory::create([
                'document_id'    => $model->id,
                'document_type'  => class_basename($model),
                'previous_status'=> $model->getOriginal('status'),
                'current_status' => $model->status,
                'remarks'        => $model->reason,
                'created_by'     => $model->updated_by,
                'created_at'     => now(),
            ]);
        }
    }
    public function updating(Model $model)
    {
        if ($model->isDirty('status')) {
            DocumentHistory::create([
                'document_id'    => $model->id,
                'document_type'  => class_basename($model),
                'previous_status'=> $model->getOriginal('status'),
                'current_status' => $model->status,
                'remarks'        => $model->reason,
                'created_by'     => $model->updated_by,
                'created_at'     => now(),
            ]);
        }
    }
}
