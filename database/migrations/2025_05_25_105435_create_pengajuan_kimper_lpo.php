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
        Schema::create('pengajuan_kimper_lpo', function (Blueprint $table) {
            $table->id();
            $table->string('id_pengajuan_kimper');
            $table->string('type_lpo');
            $table->string('upload_lpo');
            $table->integer('instrumen_panel');
            $table->integer('safety_operasi');
            $table->integer('metode_operasi');
            $table->integer('perawatan');
            $table->integer('nilai_total');
            $table->enum('status_lpo', [0, 1])->nullable();
            $table->string('catatan_lpo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_kimper_lpo');
    }
};
