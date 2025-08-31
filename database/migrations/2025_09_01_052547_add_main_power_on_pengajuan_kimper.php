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
        Schema::table('pengajuan_kimper', function (Blueprint $table) {
            $table->integer('nilai_main_power')->nullable()->after('catatan_upload_sertifikat');
            $table->integer('nilai_tes_rambu')->nullable()->change();
            $table->integer('nilai_tes_teori')->nullable()->change();
            $table->integer('nilai_tes_p2h')->nullable()->change();
            $table->integer('nilai_tes_praktek')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
