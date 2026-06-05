<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            if (!Schema::hasColumn('vouchers', 'service_type')) {
                $table->enum('service_type', [
                    'all',
                    'food',
                    'ojek',
                    'car'
                ])->default('all')->after('type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            if (Schema::hasColumn('vouchers', 'service_type')) {
                $table->dropColumn('service_type');
            }
        });
    }
};