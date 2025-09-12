<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id', 'event_type', 'description', 'route', 'previous_data', 'current_data', 'ip_address', 'user_agent'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}