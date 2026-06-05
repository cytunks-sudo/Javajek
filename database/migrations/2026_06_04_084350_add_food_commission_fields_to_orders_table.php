<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'food_original_total')) {
                $table->decimal('food_original_total', 15, 2)->default(0)->after('total');
            }

            if (!Schema::hasColumn('orders', 'food_markup_amount')) {
                $table->decimal('food_markup_amount', 15, 2)->default(0)->after('food_original_total');
            }

            if (!Schema::hasColumn('orders', 'delivery_commission_amount')) {
                $table->decimal('delivery_commission_amount', 15, 2)->default(0)->after('food_markup_amount');
            }

            if (!Schema::hasColumn('orders', 'admin_commission_amount')) {
                $table->decimal('admin_commission_amount', 15, 2)->default(0)->after('delivery_commission_amount');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'food_original_total',
                'food_markup_amount',
                'delivery_commission_amount',
                'admin_commission_amount',
            ]);
        });
    }
};