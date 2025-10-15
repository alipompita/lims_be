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
        Schema::create('test_parameters', function (Blueprint $table) {
            $table->id();
            // $table->integer('test_type_id')->nullable();
            $table->foreignId('test_type_id')->constrained('test_types')->onDelete('cascade');
            $table->string('name');
            $table->string('type')->nullable();
            $table->string('description')->nullable();
            $table->string('unit')->nullable();
            // $table->float('normal_range_min')->nullable();
            // $table->float('normal_range_max')->nullable();
            $table->decimal('normal_range_min', 12, 11)->nullable();
            $table->decimal('normal_range_max', 12, 11)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_parameters');
    }
};
