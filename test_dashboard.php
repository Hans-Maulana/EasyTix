<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$orgId = 1; // Assuming an organizer ID

$approvedRequests = \App\Models\EventRequest::where('status', 'approved')->get();
$myEventIds = $approvedRequests->pluck('event_id');

$totalTicketsValid = \App\Models\OrderDetail::whereHas('ticket.event_schedule.event', function($q) use ($myEventIds) {
    if(count($myEventIds) > 0) {
        $q->whereIn('id', $myEventIds);
    }
})->where('status', 'valid')->count();

$totalRevenue = \App\Models\Order::whereIn('events_id', $myEventIds)->sum('total_amount');

echo "Total Tickets: $totalTicketsValid\n";
echo "Total Revenue: $totalRevenue\n";
