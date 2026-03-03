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
        Schema::create('storage_worksheet_samples', function (Blueprint $table) {
            $table->id();
            $table->foreignId('worksheet_id')->constrained('worksheets')->onDelete('cascade');
            $table->string('labno');
            $table->foreign('labno')->references('labno')->on('specimens')->onDelete('cascade');
            $table->foreignId('freezer_id')->constrained('freezers')->onDelete('cascade');
            $table->foreignId('aliquot_id')->constrained('aliquots')->onDelete('cascade');
            $table->integer('rack')->nullable();
            $table->integer('box')->nullable();
            $table->string('position', 4)->nullable();
            $table->boolean('stored')->default(false);
            $table->date('storage_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('storage_worksheet_samples');
    }
};
