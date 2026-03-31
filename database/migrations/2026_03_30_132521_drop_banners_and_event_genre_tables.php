<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('event_genre');
        Schema::dropIfExists('banners');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('image');
            $table->string('link')->nullable();
            $table->string('status');
            $table->string('type');
            $table->timestamps();
        });

        Schema::create('event_genre', function (Blueprint $table) {
            $table->id();
            $table->string('event_id');
            $table->foreignId('genre_id')->constrained();
            $table->timestamps();
        });
    }
};
