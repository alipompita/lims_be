<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE `users` MODIFY COLUMN `role` ENUM('admin', 'lab_tech', 'data_officer') NOT NULL DEFAULT 'lab_tech'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE `users` MODIFY COLUMN `role` ENUM('admin', 'lab_tech') NOT NULL DEFAULT 'lab_tech'");
    }
};
