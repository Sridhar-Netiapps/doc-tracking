<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use App\Models\DocumentHistory;
use Illuminate\Support\Facades\Auth;

class DocumentObserver
{
    public function created(Model $model)
    {
        if ($model->isDirty('status')) {
            DocumentHistory::create([
                'document_id'    => $model->id,
                'document_type'  => class_basename($model),
                'previous_status'=> $model->getOriginal('status'),
                'current_status' => $model->status,
                'remarks'        => $model->reason,
                'created_by'     => 0,
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
    public function deleting(Model $model)
    {
        if (method_exists($model, 'isForceDeleting') && !$model->isForceDeleting()) {
            DocumentHistory::create([
                'document_id'     => $model->id,
                'document_type'   => class_basename($model),
                'previous_status' => $model->getOriginal('status'),
                'current_status'  => 'Moved to Trash',
                'remarks'         => $model->reason ?? 'Moved to Trash',
                'created_by'      => $model->deleted_by ?? 0,
                'created_at'      => now(),
            ]);
        }
    }

    public function restored(Model $model)
    {
        DocumentHistory::create([
            'document_id'     => $model->id,
            'document_type'   => class_basename($model),
            'previous_status' => 'deleted',
            'current_status'  => 1,
            'remarks'         => $model->reason ?? 'Record restored',
            'created_by'      => auth()->id() ?? 0,
            'created_at'      => now(),
        ]);
    }
}
