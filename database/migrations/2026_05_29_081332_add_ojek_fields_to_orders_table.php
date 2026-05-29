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
    Schema::table('orders', function (Blueprint $table) {
        if (!Schema::hasColumn('orders', 'order_type')) {
            $table->string('order_type')->nullable()->after('user_id');
        }

        if (!Schema::hasColumn('orders', 'pickup_address')) {
            $table->text('pickup_address')->nullable();
        }

        if (!Schema::hasColumn('orders', 'destination_address')) {
            $table->text('destination_address')->nullable();
        }

        if (!Schema::hasColumn('orders', 'distance_km')) {
            $table->decimal('distance_km', 10, 2)->nullable();
        }
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
