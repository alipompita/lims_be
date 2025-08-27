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
        Schema::create('freezers', function (Blueprint $table) {
            $table->id();
            $table->string('label')->unique();
            $table->string('site_code')->unique();
            $table->string('make')->nullable();
            $table->string('model')->nullable();
            $table->string('description')->nullable();
            $table->string('location')->nullable();
            $table->timestamps();

            $table->foreign('site_code')->references('code')->on('sites')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('freezers');
    }
};
