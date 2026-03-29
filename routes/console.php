<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Services\Pets\Models\Application;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    Application::where('status', 'rejected')
        ->where('updated_at', '<', now()->subWeeks(2))
        ->delete();
})->daily();