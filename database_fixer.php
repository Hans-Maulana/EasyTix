<?php
// database_fixer.php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

echo "--- EasyTix Database Fixer ---\n";

try {
    // 1. Fix waiting_lists table
    if (Schema::hasTable('waiting_lists')) {
        Schema::table('waiting_lists', function (Blueprint $table) {
            // Add priority column if not exists
            if (!Schema::hasColumn('waiting_lists', 'priority')) {
                $table->integer('priority')->default(0)->after('quantity');
                echo "SUCCESS: Column 'priority' added to 'waiting_lists'.\n";
            } else {
                echo "INFO: Column 'priority' already exists.\n";
            }
        });

        // Change status column to VARCHAR (no matter what it was before)
        DB::statement("ALTER TABLE waiting_lists MODIFY COLUMN status VARCHAR(20) DEFAULT 'pending'");
        echo "SUCCESS: 'status' column in 'waiting_lists' fixed to VARCHAR(20).\n";
    }

    // 2. Create organizer_slot_requests if not exists
    if (!Schema::hasTable('organizer_slot_requests')) {
        Schema::create('organizer_slot_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('waiting_list_id')->constrained('waiting_lists')->onDelete('cascade');
            $table->foreignId('organizer_id')->constrained('users')->onDelete('cascade');
            $table->integer('requested_quantity');
            $table->string('status')->default('pending');
            $table->text('admin_note')->nullable();
            $table->timestamps();
        });
        echo "SUCCESS: Table 'organizer_slot_requests' created.\n";
    } else {
        echo "INFO: Table 'organizer_slot_requests' already exists.\n";
    }

    // 3. Fix orders table
    if (Schema::hasTable('orders')) {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'email')) {
                $table->string('email')->nullable();
                echo "SUCCESS: Column 'email' added to 'orders'.\n";
            }
        });
    }

    echo "--- All Database Fixes Applied! ---\n";
    echo "You can now delete this file (database_fixer.php).\n";

} catch (\Exception $e) {
    echo "CRITICAL ERROR: " . $e->getMessage() . "\n";
}
