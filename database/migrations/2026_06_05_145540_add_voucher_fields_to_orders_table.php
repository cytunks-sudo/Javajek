<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'voucher_id')) {
                $table->unsignedBigInteger('voucher_id')->nullable()->after('grand_total');
            }

            if (!Schema::hasColumn('orders', 'voucher_code')) {
                $table->string('voucher_code')->nullable()->after('voucher_id');
            }

            if (!Schema::hasColumn('orders', 'voucher_discount')) {
                $table->decimal('voucher_discount', 12, 0)->default(0)->after('voucher_code');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'voucher_id')) {
                $table->dropColumn('voucher_id');
            }

            if (Schema::hasColumn('orders', 'voucher_code')) {
                $table->dropColumn('voucher_code');
            }

            if (Schema::hasColumn('orders', 'voucher_discount')) {
                $table->dropColumn('voucher_discount');
            }
        });
    }
};