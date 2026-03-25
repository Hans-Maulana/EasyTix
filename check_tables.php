<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;

$tables = ['orders', 'order_details', 'order_items'];
foreach ($tables as $table) {
    if (Schema::hasTable($table)) {
        echo "Table $table exists.\n";
    } else {
        echo "Table $table does NOT exist.\n";
    }
}
