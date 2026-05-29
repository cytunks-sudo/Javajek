<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('ride_settings', function (Blueprint $table) {
        $table->id();
        $table->decimal('base_fee', 12, 2)->default(5000);
        $table->decimal('per_km_fee', 12, 2)->default(2500);
        $table->decimal('minimum_fee', 12, 2)->default(8000);
        $table->timestamps();
    });

    DB::table('ride_settings')->insert([
        'base_fee' => 5000,
        'per_km_fee' => 2500,
        'minimum_fee' => 8000,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ride_settings');
    }
};
