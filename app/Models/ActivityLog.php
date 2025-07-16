<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id', 'event_type', 'description', 'route', 'ip_address', 'user_agent'
    ];
}
