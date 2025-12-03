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
        Schema::create('sample_collection_requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('study_acc_form_id')->constrained('study_acc_forms')->onDelete('cascade');
            $table->foreignId('spectype')->constrained('specimen_types')->onDelete('cascade');
            $table->float('volume_required')->nullable();
            $table->string('volume_unit')->nullable();
            $table->float('recommended_shipping_temperature')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sample_collection_requirements');
    }
};
