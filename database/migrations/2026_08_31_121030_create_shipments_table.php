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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_site_id')->nullable()->constrained('sites')->onDelete('set null');
            $table->foreignId('to_site_id')->nullable()->constrained('sites')->onDelete('set null');
            $table->foreignId('shipped_by')->constrained('users')->onDelete('cascade');
            $table->date('date_shipped');
            $table->foreignId('received_by')->nullable()->constrained('users')->onDelete('set null');
            $table->date('date_recceived')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
