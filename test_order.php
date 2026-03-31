<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$schedule = \App\Models\EventSchedule::first();
echo "Schedule ID: " . $schedule->id . "\n";

$attendees = \App\Models\OrderDetail::whereIn('status', ['valid', 'used'])
    ->whereHas('ticket', function($q) use ($schedule) {
        $q->where('event_schedules_id', $schedule->id);
    })->get();

echo "Count attendees: " . $attendees->count() . "\n";
