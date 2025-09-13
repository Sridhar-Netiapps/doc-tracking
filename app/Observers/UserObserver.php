<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Log;
use Illuminate\Http\Request;



class UserObserver
{
    public function created(Model $model)
    {
        if ($model->isDirty()) {
            $request = request();
            ActivityLog::create([
                'user_id'    => $model->id,
                'event_type'  => 'user-creation',
                'description' => 'Created: ' . $request->path(),
                'route' => $request->path(),
                'previous_data' => json_encode($model->getOriginal(), JSON_PRETTY_PRINT),
                'current_data'  => json_encode($model->getAttributes(), JSON_PRETTY_PRINT),
                'remarks'        => $model->reason,
                'created_by'     => 0,
                'created_at'     => now(),
            ]);
        }
    }
    public function updating(Model $model)
    {
        if ($model->isDirty()) {
            $request = request();
            ActivityLog::create([
                'user_id'    => $model->id,
                'event_type'  => 'user-update',
                'description' => 'Updated: ' . $request->path(),
                'route' => $request->path(),
                'previous_data' => json_encode($model->getOriginal(), JSON_PRETTY_PRINT),
                'current_data'  => json_encode($model->getAttributes(), JSON_PRETTY_PRINT),
                'remarks'        => $model->reason,
                'created_by'     => 0,
                'created_at'     => now(),
            ]);
        }
    }
}