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
        Schema::create('aliquots', function (Blueprint $table) {
            $table->id();
            $table->string('labno');
            $table->foreign('labno')->references('labno')->on('specimens')->onDelete('cascade');
            $table->double('volume')->nullable();
            $table->foreignId('created_at_site_id')->nullable()->constrained('sites')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('current_freezer_id')->nullable()->constrained('freezers')->nullOnDelete();
            $table->integer('current_rack')->nullable();
            $table->integer('current_box')->nullable();
            $table->string('current_position', 4)->nullable();
            $table->integer('thaw_count')->default(0);
            $table->boolean('is_disposed')->default(false);
            $table->foreignId('disposed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('disposed_at')->nullable();
            $table->foreignId('disposed_at_site_id')->nullable()->constrained('sites')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aliquots');
    }
};
