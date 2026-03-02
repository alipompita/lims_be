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
        Schema::table('worksheets', function (Blueprint $table) {
            $table->foreignID('created_by')->nullable()->constrained('users')->onDelete('set null')->after('test_type_id');
            $table->foreignId('created_at_site_id')->nullable()->constrained('sites')->onDelete('set null')->after('created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('worksheets', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');
            $table->dropForeign(['created_at_site_id']);
            $table->dropColumn('created_at_site_id');
        });
    }
};
