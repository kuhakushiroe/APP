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
            $table->renameColumn('nilai_tes_rambu', 'nilai_instrumen_panel');
            $table->renameColumn('nilai_tes_teori', 'nilai_safety_operasi');
            $table->renameColumn('nilai_tes_p2h', 'nilai_metode_operasi');
            $table->renameColumn('nilai_tes_praktek', 'nilai_perawatan');
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
