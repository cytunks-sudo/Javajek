<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            if (!Schema::hasColumn('orders', 'customer_latitude')) {
                $table->decimal('customer_latitude', 10, 7)->nullable()->after('driver_id');
            }

            if (!Schema::hasColumn('orders', 'customer_longitude')) {
                $table->decimal('customer_longitude', 10, 7)->nullable()->after('customer_latitude');
            }

            if (!Schema::hasColumn('orders', 'merchant_latitude')) {
                $table->decimal('merchant_latitude', 10, 7)->nullable()->after('customer_longitude');
            }

            if (!Schema::hasColumn('orders', 'merchant_longitude')) {
                $table->decimal('merchant_longitude', 10, 7)->nullable()->after('merchant_latitude');
            }

            if (!Schema::hasColumn('orders', 'driver_latitude')) {
                $table->decimal('driver_latitude', 10, 7)->nullable()->after('merchant_longitude');
            }

            if (!Schema::hasColumn('orders', 'driver_longitude')) {
                $table->decimal('driver_longitude', 10, 7)->nullable()->after('driver_latitude');
            }

            if (!Schema::hasColumn('orders', 'distance_km')) {
                $table->decimal('distance_km', 8, 2)->default(0)->after('driver_longitude');
            }

            if (!Schema::hasColumn('orders', 'delivery_fee')) {
                $table->integer('delivery_fee')->default(0)->after('distance_km');
            }

            if (!Schema::hasColumn('orders', 'grand_total')) {
                $table->integer('grand_total')->default(0)->after('delivery_fee');
            }
        });

        Schema::table('drivers', function (Blueprint $table) {

            if (!Schema::hasColumn('drivers', 'last_location_update')) {
                $table->timestamp('last_location_update')->nullable()->after('longitude');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            foreach ([
                'customer_latitude',
                'customer_longitude',
                'merchant_latitude',
                'merchant_longitude',
                'driver_latitude',
                'driver_longitude',
                'distance_km',
                'delivery_fee',
                'grand_total',
            ] as $column) {
                if (Schema::hasColumn('orders', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('drivers', function (Blueprint $table) {
            if (Schema::hasColumn('drivers', 'last_location_update')) {
                $table->dropColumn('last_location_update');
            }
        });
    }
};