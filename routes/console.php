<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Log;
use App\Console\Commands\ExportInsuranceLeadsCommand;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('app:export-insurance-leads', function () {
    $this->call(ExportInsuranceLeadsCommand::class);
});



Schedule::command('documents:revert-draft')->dailyAt('06:00');
Schedule::command('documents:sync')->dailyAt('06:30');
Schedule::command('documents:sync-email')->dailyAt('07:00');
Schedule::command('app:sync-h-r-mdata')->dailyAt('07:40');
//Schedule::command('insurance:export-all insurance_leads')->dailyAt('07:40');
Schedule::command('insurance:export-all insurance_leads')->dailyAt("09:20");
Schedule::command('insurance:export-all insurance_leads')->dailyAt("09:20");
Schedule::command('insurance:export-all insurance_leads')->dailyAt("09:20");