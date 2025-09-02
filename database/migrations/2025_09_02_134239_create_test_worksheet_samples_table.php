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
        Schema::create('test_worksheet_samples', function (Blueprint $table) {
            $table->id();
            $table->foreignId('worksheet_id')->constrained('worksheets')->onDelete('cascade');
            $table->string('labno')->nullable();
            $table->foreign('labno')->references('labno')->on('specimens');
            $table->unsignedBigInteger('test_results_id')->nullable();
            $table->foreign('test_results_id')->references('testno')->on('test_results');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_worksheet_samples');
    }
};
