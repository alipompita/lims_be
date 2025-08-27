<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('specimen_types', function (Blueprint $table) {
            $table->id();
            $table->string('code_label')->unique();
            $table->string('code')->unique();
            $table->string('label')->unique();
            $table->string('description')->nullable();
            $table->integer('transport_method')->nullable();
            $table->boolean('has_aliquot')->default(false);
            $table->boolean('is_placenta_tissue')->default(true);
            $table->integer('total_aliquots')->default(true);
            $table->boolean('taken_from_blood')->default(1);
            $table->boolean('is_active')->default(true);

            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();



            $table->index(['is_active']);
            $table->index('code_label');
            $table->index('code');
            $table->index('label');


            $table->softDeletes();
        });

        // DB::statement('CREATE INDEX specimen_types_code_label_index 
        //         ON specimen_types (code_label(100), code(100), label(100))');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('specimen_types');
    }
};
