<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\SendDailyForecastSms;
use App\Console\Commands\DetectAndSendForecastChanges;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command(SendDailyForecastSms::class)->dailyAt('07:00');
Schedule::command(DetectAndSendForecastChanges::class)->hourly()->between('8:00', '20:00');
