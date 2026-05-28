<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('base_fee')->default(3000);
            $table->integer('per_km_fee')->default(2000);
            $table->integer('minimum_fee')->default(5000);
            $table->integer('max_driver_radius_km')->default(5);
            $table->timestamps();
        });

        DB::table('delivery_settings')->insert([
            'base_fee' => 3000,
            'per_km_fee' => 2000,
            'minimum_fee' => 5000,
            'max_driver_radius_km' => 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_settings');
    }
};