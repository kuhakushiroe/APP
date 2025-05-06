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
        Schema::create('pengajuan_kimper', function (Blueprint $table) {
            $table->id();
            $table->string('nrp');
            $table->enum('jenis_pengajuan_id', ['baru', 'penambahan', 'penghapusan', 'perpanjangan']);

            $table->string('upload_id')->nullable();
            $table->enum('status_upload_id', [0, 1])->nullable();
            $table->string('catatan_upload_id')->nullable();

            $table->string('upload_kimper_lama')->nullable();
            $table->enum('status_upload_kimper_lama', [0, 1])->nullable();
            $table->string('catatan_upload_kimper_lama')->nullable();

            $table->string('upload_request')->nullable();
            $table->enum('status_upload_request', [0, 1])->nullable();
            $table->string('catatan_upload_request')->nullable();

            $table->enum('jenis_sim', ['A', 'B', 'B1', 'B1 UMUM', 'B2', 'B2 UMUM',])->nullable();
            $table->string('upload_sim')->nullable();
            $table->enum('status_upload_sim', [0, 1])->nullable();
            $table->string('catatan_upload_sim')->nullable();

            $table->string('upload_sertifikat')->nullable();
            $table->enum('status_upload_sertifikat', [0, 1])->nullable();
            $table->string('catatan_upload_sertifikat')->nullable();

            $table->string('upload_lpo')->nullable();
            $table->enum('status_upload_lpo', [0, 1])->nullable();
            $table->string('catatan_upload_lpo')->nullable();

            $table->string('upload_foto')->nullable();
            $table->enum('status_upload_foto', [0, 1])->nullable();
            $table->string('catatan_upload_foto')->nullable();

            $table->string('upload_ktp')->nullable();
            $table->enum('status_upload_ktp', [0, 1])->nullable();
            $table->string('catatan_upload_ktp')->nullable();

            $table->string('upload_skd')->nullable();
            $table->enum('status_upload_skd', [0, 1])->nullable();
            $table->string('catatan_upload_skd')->nullable();

            $table->string('upload_bpjs')->nullable();
            $table->enum('status_upload_bpjs', [0, 1])->nullable();
            $table->string('catatan_upload_bpjs')->nullable();

            $table->text('versatility')->nullable();

            $table->enum('status_pengajuan', [0, 1, 2])->nullable();
            $table->date('tgl_pengajuan')->nullable();
            $table->date('exp_kimper')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_kimper');
    }
};
