<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\UpdatePlayerStatuses;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// This inline command is just a demo placeholder (can be removed if using UpdatePlayerStatuses class)
Artisan::command('players:update-status', function () {
    $this->info('Statuses updated.');
})->purpose('Update player statuses');

// ✅ Correct way: pass the class name using ::class
Schedule::command(UpdatePlayerStatuses::class)->dailyAt('06:10');
