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
        Schema::create('study_storage_requirements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('study_id');
            $table->unsignedBigInteger('spectype');
            $table->integer('aliqotes')->default(1);
            $table->foreign('study_id')->references('id')->on('studies')->onDelete('cascade');
            $table->foreign('spectype')->references('id')->on('specimen_types')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('study_storage_requirements');
    }
};
