<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\UpdatePlayerStatuses;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('players:update-status', function () {
    // your logic here...
    $this->info('Statuses updated.');
})->purpose('Update player statuses');

Schedule::command(UpdatePlayerStatuses::class)->everyMinute();

