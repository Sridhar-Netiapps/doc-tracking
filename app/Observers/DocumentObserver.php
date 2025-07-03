<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use App\Models\DocumentHistory;
use Illuminate\Support\Facades\Auth;
use Log;

class DocumentObserver
{
    public function updating(Model $model)
    {
        \Log::info('Observer triggered for:', [
            'model' => get_class($model),
            'id' => $model->id,
            'from' => $model->getOriginal('status'),
            'to' => $model->status
        ]);
        if ($model->isDirty('status')) {
            DocumentHistory::create([
                'document_id'    => $model->id,
                'document_type'  => class_basename($model), // e.g., "LoanDocument"
                'previous_status'=> $model->getOriginal('status'),
                'current_status' => $model->status,
                'remarks'        => $model->reason,
                'created_by'     => Auth::id(),
                'created_at'     => now(),
            ]);
        }
    }
}
