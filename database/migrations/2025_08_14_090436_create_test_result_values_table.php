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
        Schema::create('test_result_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('testno')->constrained('test_results', 'testno')->onDelete('cascade');
            $table->foreignId('test_parameter_id')->constrained('test_parameters')->onDelete('cascade');
            $table->string('value')->nullable();
            $table->string('flag')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_result_values');
    }
};
