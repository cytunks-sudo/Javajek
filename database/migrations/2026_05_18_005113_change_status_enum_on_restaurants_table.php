<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE restaurants MODIFY status ENUM('pending','active','rejected','open','closed') NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE restaurants MODIFY status ENUM('open','closed') NOT NULL DEFAULT 'closed'");
    }
};