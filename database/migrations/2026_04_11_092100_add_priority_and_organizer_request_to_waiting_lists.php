<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('waiting_lists', function (Blueprint $table) {
            $table->integer('priority')->default(0)->after('quantity');
        });

        Schema::create('organizer_slot_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('waiting_list_id')->constrained('waiting_lists')->onDelete('cascade');
            $table->foreignId('organizer_id')->constrained('users')->onDelete('cascade');
            $table->integer('requested_quantity');
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->text('admin_note')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('organizer_slot_requests');
        Schema::table('waiting_lists', function (Blueprint $table) {
            $table->dropColumn('priority');
        });
    }
};
