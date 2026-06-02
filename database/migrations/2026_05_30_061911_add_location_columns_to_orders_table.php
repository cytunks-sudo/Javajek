<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('orders', function (Blueprint $table) {
        $table->decimal('latitude', 10, 7)->nullable();
        $table->decimal('longitude', 10, 7)->nullable();

        $table->decimal('pickup_latitude', 10, 7)->nullable();
        $table->decimal('pickup_longitude', 10, 7)->nullable();

        $table->decimal('destination_latitude', 10, 7)->nullable();
        $table->decimal('destination_longitude', 10, 7)->nullable();
    });
}

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'latitude',
                'longitude',
                'pickup_latitude',
                'pickup_longitude',
                'destination_latitude',
                'destination_longitude',
            ]);
        });
    }
};