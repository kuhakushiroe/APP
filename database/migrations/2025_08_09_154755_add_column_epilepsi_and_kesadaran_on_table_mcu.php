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
        Schema::table('mcu', function (Blueprint $table) {
            //
            $table->enum('epilepsi', ['yes', 'no'])->nullable()->after('saran_mcu');
            $table->enum('kesadaran', ['yes', 'no'])->nullable()->after('epilepsi');
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
