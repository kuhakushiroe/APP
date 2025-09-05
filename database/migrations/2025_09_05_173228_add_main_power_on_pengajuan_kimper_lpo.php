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
        //
        Schema::table('pengajuan_kimper_lpo', function (Blueprint $table) {
            $table->integer('main_power')->nullable()->after('upload_lpo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('pengajuan_kimper_lpo', function (Blueprint $table) {
            $table->dropColumn('main_power');
        });
    }
};
