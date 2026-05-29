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
    Schema::create('driver_vehicles', function (Blueprint $table) {
        $table->id();
        $table->foreignId('driver_id')->constrained('drivers')->cascadeOnDelete();

        $table->string('vehicle_type')->default('motor'); 
        // motor / mobil

        $table->string('plate_number')->nullable();
        $table->string('vehicle_brand')->nullable();
        $table->string('vehicle_color')->nullable();

        $table->boolean('is_active')->default(false);

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_vehicles');
    }
};
