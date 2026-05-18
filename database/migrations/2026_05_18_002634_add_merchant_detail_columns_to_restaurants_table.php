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
    Schema::table('restaurants', function (Blueprint $table) {
        if (!Schema::hasColumn('restaurants', 'photo')) {
            $table->string('photo')->nullable()->after('address');
        }

        if (!Schema::hasColumn('restaurants', 'category')) {
            $table->string('category')->nullable()->after('photo');
        }

        if (!Schema::hasColumn('restaurants', 'open_time')) {
            $table->time('open_time')->nullable()->after('category');
        }

        if (!Schema::hasColumn('restaurants', 'close_time')) {
            $table->time('close_time')->nullable()->after('open_time');
        }
    });
}

public function down(): void
{
    Schema::table('restaurants', function (Blueprint $table) {
        $table->dropColumn([
            'photo',
            'category',
            'open_time',
            'close_time',
        ]);
    });
}

};
