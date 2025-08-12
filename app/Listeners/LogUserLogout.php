<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogUserLogout
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        \App\Models\ActivityLog::create([
            'user_id' => $event->user->id,
            'event_type' => 'logout',
            'description' => 'User logged out',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'route' => request()->path(),
        ]);
    }
}
