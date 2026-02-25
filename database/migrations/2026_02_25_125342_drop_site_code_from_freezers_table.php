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
        Schema::table('freezers', function (Blueprint $table) {
            Schema::table('freezers', function (Blueprint $table) {
                $table->dropForeign(['site_code']);
                $table->dropColumn('site_code');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('freezers', function (Blueprint $table) {
            $table->string('site_code')->unique();
            $table->foreign('site_code')->references('code')->on('sites')->onDelete('cascade');
        });
    }
};
