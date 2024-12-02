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
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->foreignUuid('sender_uuid')->references('uuid')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('content');
            $table->string('title')->nullable();
            $table->string('type')->nullable();
            $table->string('icon')->nullable();
            $table->string('reference_type');
            $table->string('reference_uuid')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
