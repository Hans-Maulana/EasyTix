<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

if (!Schema::hasTable('waiting_lists')) {
    Schema::create('waiting_lists', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->string('ticket_id');
        $table->foreign('ticket_id')->references('id')->on('tickets')->cascadeOnDelete();
        $table->integer('quantity')->default(1);
        $table->enum('status', ['pending', 'requested', 'approved', 'rejected'])->default('pending');
        $table->timestamps();
    });
    echo "Table waiting_lists created successfully.\n";
} else {
    echo "Table waiting_lists already exists.\n";
}
