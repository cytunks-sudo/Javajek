<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_settings', function (Blueprint $table) {

            $table->id();

            $table->string('app_name')->default('JavaJek');

            $table->string('login_logo')->nullable();
            $table->string('customer_logo')->nullable();
            $table->string('driver_logo')->nullable();
            $table->string('merchant_logo')->nullable();

            $table->string('driver_map_icon')->nullable();

            $table->string('home_banner')->nullable();

            $table->string('primary_color')->default('#f97316');
            $table->string('secondary_color')->default('#fb923c');

            $table->boolean('maintenance_mode')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_settings');
    }
};