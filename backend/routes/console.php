<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('app:pathway-action-engine')->daily();

Schedule::call(function () {
    $sources = \App\Models\ScholarshipSource::where('is_active', true)->get();
    foreach ($sources as $source) {
        \App\Jobs\FetchScholarshipsJob::dispatch($source);
    }
})->daily()->name('fetch-scholarships');

// Check deadlines and notify users daily at 8 AM
Schedule::command('app:check-deadlines')->dailyAt('08:00')->name('check-deadlines');
