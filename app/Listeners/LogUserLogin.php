<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogUserLogin
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
        if (auth()->check() && !auth()->user()->hasRole('master')) {
            \App\Models\ActivityLog::create([
                'user_id' => $event->user->id,
                'event_type' => 'login',
                'description' => 'User logged in',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'route' => request()->path(),
            ]);
        }
    }
}
