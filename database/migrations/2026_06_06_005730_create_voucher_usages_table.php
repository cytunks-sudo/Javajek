<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('voucher_usages', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('voucher_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();

            $table->string('voucher_code')->nullable();
            $table->string('service_type')->default('food');
            $table->decimal('discount_amount', 15, 2)->default(0);

            $table->timestamps();

            $table->index('voucher_id');
            $table->index('user_id');
            $table->index('order_id');
            $table->index('service_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voucher_usages');
    }
};