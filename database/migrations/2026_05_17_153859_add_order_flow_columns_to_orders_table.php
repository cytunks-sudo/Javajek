<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            if (!Schema::hasColumn('orders', 'restaurant_id')) {
                $table->unsignedBigInteger('restaurant_id')->nullable()->after('user_id');
            }

            if (!Schema::hasColumn('orders', 'driver_id')) {
                $table->unsignedBigInteger('driver_id')->nullable()->after('restaurant_id');
            }

            if (!Schema::hasColumn('orders', 'merchant_status')) {
                $table->string('merchant_status')->default('pending')->after('status');
            }

            if (!Schema::hasColumn('orders', 'driver_status')) {
                $table->string('driver_status')->default('pending')->after('merchant_status');
            }

            if (!Schema::hasColumn('orders', 'driver_reject_count')) {
                $table->integer('driver_reject_count')->default(0)->after('driver_status');
            }

        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            if (Schema::hasColumn('orders', 'merchant_status')) {
                $table->dropColumn('merchant_status');
            }

            if (Schema::hasColumn('orders', 'driver_status')) {
                $table->dropColumn('driver_status');
            }

            if (Schema::hasColumn('orders', 'driver_reject_count')) {
                $table->dropColumn('driver_reject_count');
            }

        });
    }
};