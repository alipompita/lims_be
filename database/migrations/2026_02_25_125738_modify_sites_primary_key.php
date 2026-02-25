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
        Schema::table('sites', function (Blueprint $table) {
            $table->dropPrimary();      // drop PK on code
            $table->dropColumn('code'); // remove old PK column
            $table->id()->first();               // creates: id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
        });
    }

    public function down(): void
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->dropColumn('id');
            $table->string('code', 4)->primary();
        });
    }
};
