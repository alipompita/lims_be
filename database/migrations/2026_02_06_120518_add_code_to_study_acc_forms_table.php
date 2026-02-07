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
        Schema::table('study_acc_forms', function (Blueprint $table) {
            //
            $table->string('code')->nullable()->unique('study_acc_forms_code_unique')->after('study_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('study_acc_forms', function (Blueprint $table) {
            //
            $table->dropColumn('code');
        });
    }
};
