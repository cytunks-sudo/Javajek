<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ride_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('ride_settings', 'car_base_fee')) {
                $table->decimal('car_base_fee', 10, 0)->default(10000);
            }

            if (!Schema::hasColumn('ride_settings', 'car_per_km_fee')) {
                $table->decimal('car_per_km_fee', 10, 0)->default(4000);
            }

            if (!Schema::hasColumn('ride_settings', 'car_minimum_fee')) {
                $table->decimal('car_minimum_fee', 10, 0)->default(15000);
            }
        });
    }

    public function down(): void
    {
        Schema::table('ride_settings', function (Blueprint $table) {
            if (Schema::hasColumn('ride_settings', 'car_base_fee')) {
                $table->dropColumn('car_base_fee');
            }

            if (Schema::hasColumn('ride_settings', 'car_per_km_fee')) {
                $table->dropColumn('car_per_km_fee');
            }

            if (Schema::hasColumn('ride_settings', 'car_minimum_fee')) {
                $table->dropColumn('car_minimum_fee');
            }
        });
    }
};