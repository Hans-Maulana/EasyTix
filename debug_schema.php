<?php
require 'vendor/autoload.php';
use Illuminate\Support\Facades\DB;

$schema = DB::select("PRAGMA table_info(order_details)");
print_r($schema);
