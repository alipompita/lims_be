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
        Schema::create('worksheets', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->char('worksheet_type', 1);

            $table->unsignedBigInteger('test_type_id')->nullable();
            $table->foreign('test_type_id')
                ->references('id')
                ->on('test_types')
                ->onDelete('set null');

            $table->foreign('worksheet_type')
                ->references('type')
                ->on('worksheet_types')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('worksheets');
    }
};
