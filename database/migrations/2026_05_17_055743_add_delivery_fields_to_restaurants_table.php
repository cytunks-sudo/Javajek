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

        $table->integer('delivery_radius')->default(5);

        $table->decimal('delivery_fee', 12, 2)->default(0);

        $table->decimal('minimum_order', 12, 2)->default(0);

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            //
        });
    }
};
