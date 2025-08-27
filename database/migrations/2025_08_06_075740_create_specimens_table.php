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
        Schema::create('specimens', function (Blueprint $table) {
            $table->string('labno')->primary();
            $table->string('specno')->unique();
            $table->foreignId('spectype')->constrained('specimen_types')->onDelete('cascade');
            $table->string('stid')->nullable();
            $table->foreign('stid')->references('stid')->on('study_participants')->onDelete('set null');
            // $table->foreignId('site_code')->constrained('sites')->onDelete('cascade');
            $table->string('cno')->nullable();
            $table->string('accForm')->nullable();
            $table->boolean('repeat_sample')->default(false);
            $table->boolean('pregnant')->nullable();
            $table->boolean('curmens')->default(false);
            $table->boolean('mens2d')->default(false);
            $table->integer('basefoll')->nullable();
            $table->boolean('fast')->nullable();
            $table->integer('venepunc')->nullable();
            $table->float('volume')->nullable();
            $table->integer('tubes')->nullable();
            $table->integer('stooltype')->nullable();
            $table->string('stoolusual')->nullable();
            $table->integer('spectime')->nullable();
            $table->date('datecol')->nullable();
            $table->time('timeprod')->nullable();
            $table->time('timeint')->nullable();
            $table->string('iohexol')->nullable();
            $table->date('dateinlab')->nullable();
            $table->time('timeinlab')->nullable();
            $table->string('staffcode')->nullable();
            $table->foreignId('labstaff')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('checker')->nullable()->constrained('users')->onDelete('set null');

            $table->string('rcdr')->nullable();

            $table->integer('version')->default(1);


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('specimens');
    }
};
