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
        Schema::create('study_test_requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('study_id')->constrained('studies')->onDelete('cascade');
            $table->foreignId('test_type')->constrained('test_types')->onDelete('cascade');
            $table->foreignId('spectype')->constrained('specimen_types')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('study_test_requirements');
    }
};
