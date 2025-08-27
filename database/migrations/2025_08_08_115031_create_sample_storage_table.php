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
        Schema::create('sample_storage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('worksheet')->constrained('worksheets')->onDelete('cascade');
            $table->string('labno')->nullable();
            $table->foreign('labno')->references('labno')->on('specimens')->onDelete('set null');
            $table->integer('freezer_id')->nullable();
            // $table->foreign('freezer_id')->references('id')->on('freezers')->onDelete('set null');
            $table->integer('aliquot')->nullable();
            $table->integer('rack')->nullable();
            $table->integer('box')->nullable();
            $table->string('position')->nullable();
            $table->date('store_date')->nullable();
            $table->integer('thaw_count')->nullable();
            // $table->foreignId('entry_by')->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sample_storage');
    }
};
