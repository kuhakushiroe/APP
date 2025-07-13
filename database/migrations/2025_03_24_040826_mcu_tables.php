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
        schema::create('mcu', function (Blueprint $table) {
            $table->id();
            $table->string('sub_id')->nullable();
            $table->string('id_karyawan');

            $table->string('file_mcu')->nullable();
            $table->enum('status_file_mcu', [0, 1])->nullable();
            $table->string('catatan_file_mcu')->nullable();

            $table->enum('gol_darah', ['A', 'B', 'AB', 'O'])->nullable();
            $table->string('no_dokumen')->nullable();
            $table->enum('jenis_pengajuan_mcu', ['Pre Employeed MCU', 'Annual MCU', 'MCU Khusus', 'Exit MCU'])->nullable();
            $table->string('proveder')->nullable();
            $table->string('tgl_mcu');
            $table->string('exp_mcu')->nullable();
            $table->string('tgl_verifikasi')->nullable();
            $table->enum('status', ['FIT', 'FOLLOW UP', 'UNFIT', 'TEMPORARY UNFIT'])->nullable();
            $table->string('verifikator')->nullable();
            //coba rubah yazid

            $table->string('paramedik')->nullable();
            $table->enum('paramedik_status', [0, 1])->nullable();
            $table->text('paramedik_catatan')->nullable();

            $table->enum('riwayat_rokok', ['Ya', 'Tidak'])->default('Tidak')->nullable(); //baru
            //tubuh
            $table->string('BB')->nullable();
            $table->string('TB')->nullable();
            $table->string('LP')->nullable();
            $table->double('BMI')->nullable();
            $table->string('Laseq')->nullable();
            $table->enum('reqtal_touche', ['Ditemukan', 'Tidak Ditemukan'])->nullable(); // baru
            //tekanan darah
            $table->integer('sistol')->nullable();
            $table->integer('diastol')->nullable();
            //mata
            $table->string('OD_jauh')->nullable();
            $table->string('OS_jauh')->nullable();
            $table->string('OD_dekat')->nullable();
            $table->string('OS_dekat')->nullable();
            $table->enum('butawarna', ['none', 'parsial', 'total'])->default('none')->nullable();
            //Gula darah
            $table->integer('gdp')->nullable();
            $table->integer('gd_2_jpp')->nullable();
            $table->double('hba1c')->nullable(); // baru
            //Ginjal
            $table->integer('ureum')->nullable();
            $table->double('creatine')->nullable();
            $table->integer('asamurat')->nullable();
            //Fungsi Hati
            $table->integer('sgot')->nullable();
            $table->integer('sgpt')->nullable();
            $table->integer('gamma_gt')->nullable(); // baru
            $table->enum('hbsag', ['Positif', 'Negatif'])->nullable();
            //LIPID
            $table->integer('kolesterol')->nullable();
            $table->integer('hdl')->nullable();
            $table->integer('ldl')->nullable();
            $table->integer('tg')->nullable();
            //darah rutin
            $table->enum('napza', ['Positif', 'Negatif'])->nullable();
            $table->string('urin')->nullable();
            $table->string('ekg')->nullable();
            $table->string('rontgen')->nullable();
            $table->string('audiometri')->nullable();
            $table->string('spirometri')->nullable();
            $table->enum('tredmil_test', ['Hipertensif Positif', 'Hipertensif Negatif'])->nullable();
            $table->string('widal_test')->nullable();
            $table->string('echocardiography')->nullable();
            $table->string('routin_feces')->nullable();
            $table->string('kultur_feces')->nullable();


            //tidak ada
            $table->string('anti_hbs')->nullable();
            $table->string('darah_rutin')->nullable();
            //tidak ada

            $table->enum('status_', ['open', 'close'])->default('open');
            $table->string('temuan')->nullable();
            $table->string('keterangan_mcu')->nullable();
            $table->string('saran_mcu')->nullable();
            $table->softDeletes();
            $table->timestamps();
            //coba github
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('mcu');
    }
};
