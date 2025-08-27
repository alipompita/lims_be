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
        Schema::create('test_results', function (Blueprint $table) {
            $table->id('testno');
            $table->string('labno')->nullable();
            $table->foreign('labno')->references('labno')->on('specimens')->onDelete('set null');
            $table->foreignId('test_type')->constrained('test_types')->onDelete('cascade');
            $table->date('res_date')->nullable();
            $table->foreignId('test_by')->constrained('users')->onDelete('cascade');
            $table->string('noRes_comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_results');
    }
};
