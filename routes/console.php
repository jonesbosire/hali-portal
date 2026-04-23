<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Suspend members whose dues are more than 7 days overdue — runs daily at 02:00 UTC
Schedule::command('members:suspend-overdue')->dailyAt('02:00');
