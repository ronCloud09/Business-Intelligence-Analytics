<?php

use App\Jobs\GenerateDashboardInsight;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('sync:finance')->everyFifteenMinutes()->withoutOverlapping()->runInBackground();
Schedule::command('sync:inventory')->everyFifteenMinutes()->withoutOverlapping()->runInBackground();
Schedule::command('sync:manufacturing')->everyFifteenMinutes()->withoutOverlapping()->runInBackground();
Schedule::command('sync:procurement')->everyFifteenMinutes()->withoutOverlapping()->runInBackground();
Schedule::command('sync:fulfillment')->everyFifteenMinutes()->withoutOverlapping()->runInBackground();
Schedule::command('sync:ecommerce')->everyFifteenMinutes()->withoutOverlapping()->runInBackground();
Schedule::command('cache:warm-dashboard')->everyFifteenMinutes()->withoutOverlapping()->runInBackground();