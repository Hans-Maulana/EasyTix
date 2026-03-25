<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$con = Illuminate\Support\Facades\DB::connection();
try {
    $res = $con->select("SELECT TABLE_NAME, COLUMN_NAME, CONSTRAINT_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE REFERENCED_TABLE_NAME = 'orders'");
    file_put_contents('test_schema.json', json_encode($res));
} catch (\Exception $e) {
    file_put_contents('test_schema.json', $e->getMessage());
}
