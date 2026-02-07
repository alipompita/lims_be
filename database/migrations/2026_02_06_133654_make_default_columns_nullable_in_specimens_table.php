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
        Schema::table('specimens', function (Blueprint $table) {
            $table->boolean('repeat_sample')->nullable()->change();
            $table->boolean('curmens')->nullable()->change();
            $table->boolean('mens2d')->nullable()->change();
            $table->boolean('version')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('specimens', function (Blueprint $table) {
            $table->boolean('repeat_sample')->default(false)->nullable(false)->change();
            $table->boolean('curmens')->default(false)->nullable(false)->change();
            $table->boolean('mens2d')->default(false)->nullable(false)->change();
            $table->integer('version')->default(1)->nullable(false)->change();
        });
    }
};
