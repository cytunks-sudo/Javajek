<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_ratings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->foreignId('driver_id')->nullable()->constrained('drivers')->nullOnDelete();
            $table->foreignId('restaurant_id')->nullable()->constrained('restaurants')->nullOnDelete();

            $table->tinyInteger('driver_rating')->nullable();
            $table->text('driver_review')->nullable();

            $table->tinyInteger('merchant_rating')->nullable();
            $table->text('merchant_review')->nullable();

            $table->timestamps();

            $table->unique(['order_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_ratings');
    }
};