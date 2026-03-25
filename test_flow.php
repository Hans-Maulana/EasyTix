<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Ticket;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Str;

echo "--- START TEST ---" . PHP_EOL;

$user = User::first();
if (!$user) {
    echo "NO USER FOUND" . PHP_EOL; exit;
}

$ticket = Ticket::with('event_schedule.event')->first();
if (!$ticket) {
    echo "NO TICKET FOUND" . PHP_EOL; exit;
}

echo "Using User: " . $user->name . " (ID: " . $user->id . ")" . PHP_EOL;
echo "Using Ticket for Event: " . $ticket->event_schedule->event->name . " (Price: " . $ticket->price . ")" . PHP_EOL;

// 1. Process Order (Simulating OrderController@processOrder)
$year = date('Y');
$orderId = "TEST-ORD-{$year}-" . strtoupper(Str::random(5));
$order = Order::create([
    'id'          => $orderId,
    'users_id'    => $user->id,
    'events_id'   => $ticket->event_schedule->event->id,
    'total_amount' => $ticket->price,
]);

$orderDetailId = 'TEST-DET-' . $year . '-' . strtoupper(Str::random(6));
$qrString = "VERIFY-" . $orderDetailId;
$qrPath = 'qrcodes/test_qr.svg';

$orderDetail = OrderDetail::create([
    'id'          => $orderDetailId,
    'owner_name'  => $user->name,
    'status'      => 'valid',
    'tickets_id'  => $ticket->id,
    'orders_id'   => $order->id,
    'qr_code'     => $qrPath,
    'ticket_code' => $qrString, 
]);

echo "Order Created: " . $order->id . PHP_EOL;
echo "OrderDetail ID: " . $orderDetail->id . PHP_EOL;
echo "Ticket Code (Encoded in QR): " . $orderDetail->ticket_code . PHP_EOL;

// 2. Simulate Verification (Simulating EventController@processVerification)
$inputCode = $orderDetail->ticket_code;
echo "--- SIMULATING VERIFICATION ---" . PHP_EOL;
echo "Scanning code: " . $inputCode . PHP_EOL;

$verifyDetail = OrderDetail::where('ticket_code', $inputCode)->first();

if(!$verifyDetail) {
    echo "VERIFICATION FAILED: Tiket tidak ditemukan!" . PHP_EOL;
} else if ($verifyDetail->status === 'used') {
    echo "VERIFICATION FAILED: Tiket sudah digunakan!" . PHP_EOL;
} else if ($verifyDetail->status !== 'valid') {
    echo "VERIFICATION FAILED: Status tiket tidak valid!" . PHP_EOL;
} else {
    $verifyDetail->update(['status' => 'used']);
    echo "VERIFICATION SUCCESS: Tiket valid milik " . $verifyDetail->owner_name . ". Status updated to 'used'." . PHP_EOL;
}

// 3. Try to verify again
echo "--- SECOND VERIFICATION ATTEMPT ---" . PHP_EOL;
$verifyDetail2 = OrderDetail::where('ticket_code', $inputCode)->first();
if($verifyDetail2->status === 'used') {
    echo "VERIFICATION FAILED AS EXPECTED: Tiket sudah digunakan!" . PHP_EOL;
}
echo "--- TEST COMPLETE ---" . PHP_EOL;
