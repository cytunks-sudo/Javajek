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
        Schema::create('driver_wallet_transactions', function (Blueprint $table) {

    $table->id();

    $table->foreignId('driver_id');

    $table->string('type');
    /*
        topup
        commission
        adjustment
    */

    $table->decimal('amount',15,2);

    $table->decimal('balance_before',15,2);

    $table->decimal('balance_after',15,2);

    $table->text('description')->nullable();

    $table->unsignedBigInteger('order_id')->nullable();

    $table->timestamps();

});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_wallet_transactions');
    }
};
