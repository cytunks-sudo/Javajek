<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE orders MODIFY status ENUM(
            'pending',
            'waiting_response',
            'searching_driver',
            'merchant_rejected',
            'driver_to_merchant',
            'dalam_pengiriman',
            'completed',
            'cancelled'
        ) NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE orders MODIFY status ENUM(
            'pending',
            'accepted',
            'delivery',
            'completed',
            'cancelled'
        ) NOT NULL DEFAULT 'pending'");
    }
};