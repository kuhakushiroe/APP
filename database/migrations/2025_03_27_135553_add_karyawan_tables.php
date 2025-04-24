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

            $table->string('no_bantex')->nullable();
            $table->date('pengajuan_tgl_induksi')->nullable();
            $table->date('tgl_induksi')->nullable();
            $table->string('no_kimper')->nullable();

            $table->string('id_card')->nullable();
            $table->string('bpjs_kesehatan')->nullable();
            $table->string('bpjs_tenagakerja')->nullable();
            $table->string('surat_keterangan_dokter')->nullable();
            $table->string('vaksin_covid')->nullable();

            $table->string('form_permohonan_kimper')->nullable();
            $table->string('nilai_tes_rambu')->nullable();
            $table->string('nilai_tes_teori')->nullable();
            $table->string('nilai_tes_p2h')->nullable();
            $table->string('nilai_tes_praktek')->nullable();
            $table->string('ddt')->nullable();
            $table->string('hasil_evaluasi_tes_kimper')->nullable();

            $table->string('training_blasing')->nullable();
            $table->enum('klasifikasi', ['mine permit', 'mine license'])->nullable();

            $table->string('jenis_sim_polisi')->nullable();
            $table->string('nomor_simpol')->nullable();
            $table->date('exp_simpol')->nullable();

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
            $table->string('perusahaan')->nullable(); //perusahaan
            $table->string('kontraktor')->nullable(); //mitra
            $table->string('dept')->nullable();
            $table->string('jabatan')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('alamat')->nullable();
            $table->enum('domisili', ['lokal', 'non lokal'])->nullable();
            $table->enum('status', ['aktif', 'non aktif'])->nullable();

            $table->text('versatility')->nullable();

            $table->date('exp_id')->nullable();
            $table->date('exp_kimper')->nullable();
            $table->date('exp_mcu')->nullable();

            $table->date('tgl_cetak')->nullable();
            $table->string('penerima')->nullable();
            $table->string('nilai_penentu')->nullable();
            $table->string('nilai_penentu_perusahaan')->nullable();
            $table->string('peralihan')->nullable();
            $table->enum('keterangan_bantex', ['open', 'close'])->nullable();

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
