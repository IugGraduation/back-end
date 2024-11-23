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
        Schema::create('offers', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->foreignUuid('post_uuid')->references('uuid')->on('posts')->cascadeOnDelete()->cascadeOnUpdate();;
            $table->foreignUuid('user_uuid')->references('uuid')->on('users')->cascadeOnDelete()->cascadeOnUpdate();;
            $table->string('title');
            $table->string('place');
            $table->text('details');
            $table->foreignUuid('category_uuid')->references('uuid')->on('categories')->cascadeOnDelete()->cascadeOnUpdate();;
            $table->string('status')->default(0);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
