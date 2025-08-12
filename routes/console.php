<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Log;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command('documents:revert-draft')->dailyAt('06:00');
Schedule::command('documents:sync')->dailyAt('07:00');
Schedule::command('documents:sync-email')->dailyAt('08:00');
Schedule::command('app:sync-h-r-mdata')->dailyAt('16:44');