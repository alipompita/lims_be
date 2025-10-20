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
        Schema::create('sample_receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('study_id')->constrained('studies')->onDelete('cascade');
            $table->string('basefol')->nullable();
            $table->string('stid')->nullable();
            $table->foreignId('spectype')->constrained('specimen_types')->onDelete('cascade');
            $table->string('specno')->unique();
            $table->date('datecol')->nullable();
            $table->date('dateinlab')->nullable();
            $table->foreignId('entry_by')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('rejected')->default(false);
            $table->string('resrej')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sample_receipts');
    }
};
