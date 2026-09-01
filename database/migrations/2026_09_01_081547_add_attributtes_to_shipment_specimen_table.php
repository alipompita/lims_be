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
        Schema::table('shipment_specimen', function (Blueprint $table) {
            $table->enum('shipment_purpose', ['Test', 'Storage'])->after('labno');
            $table->boolean('received')->nullable()->after('unit');
            $table->enum('condition_received', ['Good', 'Damaged', 'Leaked', 'Broken Cold Chain', 'Missing', 'Other'])->nullable()->after('received');
            $table->string('condition_other')->nullable()->after('condition_received');
            $table->boolean('purpose_satisfied')->nullable()->after('condition_other');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipment_specimen', function (Blueprint $table) {
            $table->dropColumn('shipment_purpose');
            $table->dropColumn('received');
            $table->dropColumn('condition_received');
            $table->dropColumn('condition_other');
            $table->dropColumn('purpose_satisfied');
        });
    }
};
