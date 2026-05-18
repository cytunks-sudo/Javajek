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
        if (!Schema::hasColumn('restaurants', 'open_days')) {
            $table->json('open_days')->nullable()->after('close_time');
        }
    });
}

public function down(): void
{
    Schema::table('restaurants', function (Blueprint $table) {
        $table->dropColumn('open_days');
    });
}
};
