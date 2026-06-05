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
       Schema::table('orders', function () {

    DB::statement("
        ALTER TABLE orders
        MODIFY status ENUM(
            'pending',
            'waiting_response',
            'searching_driver',
            'merchant_rejected',
            'driver_to_pickup',
            'driver_to_merchant',
            'dalam_pengiriman',
            'completed',
            'cancelled'
        ) NOT NULL DEFAULT 'pending'
    ");
}); //
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
