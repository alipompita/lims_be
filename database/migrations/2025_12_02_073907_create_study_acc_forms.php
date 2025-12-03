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
        Schema::create('study_acc_forms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('study_id');
            // $table->foreignId('study_id')->constrained('studies')->onDelete('cascade');
            $table->string('form_name', 16)->nullable();
            $table->string('form_description', 256)->nullable();
            $table->boolean('is_followup')->default(false);
            $table->timestamps();
            $table->foreign('study_id')->references('id')->on('studies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('study_acc_forms');
    }
};
