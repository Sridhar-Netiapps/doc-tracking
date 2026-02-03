<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Log;
use App\Console\Commands\ExportInsuranceLeadsCommand;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command('documents:revert-draft')->dailyAt('22:00');
Schedule::command('documents:sync')->dailyAt('11:30');
Schedule::command('documents:sync-email')->dailyAt('12:00');
Schedule::command('app:sync-h-r-mdata')->dailyAt('10:00');
