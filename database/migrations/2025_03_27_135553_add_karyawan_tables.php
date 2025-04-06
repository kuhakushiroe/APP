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
        Schema::create('karyawans', function (Blueprint $table) {
            $table->id();
            $table->string('foto')->nullable();
            $table->string('nik')->nullable();
            $table->string('nrp')->unique();
            $table->date('doh');
            $table->string('nama');
            $table->date('tgl_lahir')->nullable();
            $table->enum('jenis_kelamin', ['laki-laki', 'perempuan'])->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->enum('agama', ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha', 'Konghucu'])->nullable();
            $table->enum('gol_darah', ['A', 'B', 'AB', 'O'])->nullable();
            $table->enum('status_perkawinan', ['menikah', 'belum menikah'])->nullable();
            $table->string('perusahaan')->nullable();
            $table->string('kontraktor')->nullable();
            $table->string('dept')->nullable();
            $table->string('jabatan')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('alamat')->nullable();
            $table->enum('domisili', ['lokal', 'non lokal'])->nullable();
            $table->enum('status', ['aktif', 'non aktif'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('karyawans');
    }
};
