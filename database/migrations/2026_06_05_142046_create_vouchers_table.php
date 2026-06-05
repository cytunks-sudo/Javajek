<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {

            $table->id();

            $table->string('code')->unique();
            $table->string('name');

            $table->enum('type', [
                'fixed',
                'percent',
                'free_delivery'
            ]);

            $table->decimal('value',12,0)->default(0);

            $table->decimal('minimum_order',12,0)->default(0);

            $table->decimal('maximum_discount',12,0)
                ->nullable();

            $table->integer('quota')->default(0);

            $table->integer('used_count')->default(0);

            $table->boolean('is_new_user_only')
                ->default(false);

            $table->boolean('is_active')
                ->default(true);

            $table->date('start_date')->nullable();

            $table->date('end_date')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};