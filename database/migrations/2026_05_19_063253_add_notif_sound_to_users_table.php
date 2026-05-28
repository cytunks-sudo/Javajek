<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->string('notif_sound_mode')
                ->default('default_hp')
                ->after('password');

            $table->string('notif_sound_file')
                ->nullable()
                ->after('notif_sound_mode');

        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropColumn([
                'notif_sound_mode',
                'notif_sound_file'
            ]);

        });
    }
};