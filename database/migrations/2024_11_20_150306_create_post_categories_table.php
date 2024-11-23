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
        Schema::create('post_categories', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->foreignUuid('category_uuid')->references('uuid')->on('categories')->cascadeOnDelete()->cascadeOnUpdate();;
            $table->foreignUuid('post_uuid')->references('uuid')->on('posts')->cascadeOnDelete()->cascadeOnUpdate();;

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_categories');
    }
};
