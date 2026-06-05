<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('app_settings', function (Blueprint $table) {
            $table->decimal('driver_min_balance', 15, 2)
                ->default(20000)
                ->after('customer_driver_radius');

            $table->decimal('food_price_markup_percent', 8, 2)
                ->default(10)
                ->after('driver_min_balance');

            $table->decimal('food_driver_commission_percent', 8, 2)
                ->default(20)
                ->after('food_price_markup_percent');

            $table->decimal('ride_driver_commission_percent', 8, 2)
                ->default(20)
                ->after('food_driver_commission_percent');

            $table->decimal('car_driver_commission_percent', 8, 2)
                ->default(20)
                ->after('ride_driver_commission_percent');
        });
    }

    public function down(): void
    {
        Schema::table('app_settings', function (Blueprint $table) {
            $table->dropColumn([
                'driver_min_balance',
                'food_price_markup_percent',
                'food_driver_commission_percent',
                'ride_driver_commission_percent',
                'car_driver_commission_percent',
            ]);
        });
    }
};