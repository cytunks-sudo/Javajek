<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('app_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('app_settings', 'customer_driver_radius')) {
                $table->integer('customer_driver_radius')->default(5);
            }

            if (!Schema::hasColumn('app_settings', 'ride_search_radius')) {
                $table->integer('ride_search_radius')->default(10);
            }

            if (!Schema::hasColumn('app_settings', 'merchant_radius')) {
                $table->integer('merchant_radius')->default(20);
            }
        });
    }

    public function down(): void
    {
        Schema::table('app_settings', function (Blueprint $table) {
            if (Schema::hasColumn('app_settings', 'customer_driver_radius')) {
                $table->dropColumn('customer_driver_radius');
            }

            if (Schema::hasColumn('app_settings', 'ride_search_radius')) {
                $table->dropColumn('ride_search_radius');
            }

            if (Schema::hasColumn('app_settings', 'merchant_radius')) {
                $table->dropColumn('merchant_radius');
            }
        });
    }
};