<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Artisan;

try {
    Artisan::call('migrate', [
        '--path' => 'database/migrations/2026_03_24_131641_create_orders_table.php',
        '--force' => true
    ]);
    echo Artisan::output();
} catch (\Exception $e) {
    echo $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
