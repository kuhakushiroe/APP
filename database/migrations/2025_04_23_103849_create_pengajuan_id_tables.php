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
        Schema::create('pengajuan_id', function (Blueprint $table) {
            $table->id();
            $table->string('nrp');
            $table->enum('jenis_pengajuan_id', ['baru', 'perpanjangan']);

            $table->string('upload_id_lama')->nullable();
            $table->enum('status_upload_id_lama', [0, 1])->nullable();
            $table->string('catatan_upload_id_lama')->nullable();

            $table->string('upload_request')->nullable();
            $table->enum('status_upload_request', [0, 1])->nullable();
            $table->string('catatan_upload_request')->nullable();

            $table->string('upload_induksi')->nullable();
            $table->enum('status_upload_induksi', [0, 1])->nullable();
            $table->string('catatan_upload_induksi')->nullable();
            $table->date('tgl_induksi')->nullable();

            $table->string('upload_foto')->nullable();
            $table->enum('status_upload_foto', [0, 1])->nullable();
            $table->string('catatan_upload_foto')->nullable();

            $table->string('upload_ktp')->nullable();
            $table->enum('status_upload_ktp', [0, 1])->nullable();
            $table->string('catatan_upload_ktp')->nullable();

            $table->string('upload_bpjs_kes')->nullable();
            $table->enum('status_upload_bpjs_kes', [0, 1])->nullable();
            $table->string('catatan_upload_bpjs_kes')->nullable();

            $table->string('upload_bpjs_ker')->nullable();
            $table->enum('status_upload_bpjs_ker', [0, 1])->nullable();
            $table->string('catatan_upload_bpjs_ker')->nullable();

            $table->string('upload_skd')->nullable();
            $table->enum('status_upload_skd', [0, 1])->nullable();
            $table->string('catatan_upload_skd')->nullable();

            $table->string('upload_spdk')->nullable();
            $table->enum('status_upload_spdk', [0, 1])->nullable();
            $table->string('catatan_upload_spdk')->nullable();

            $table->enum('status_pengajuan', [0, 1, 2])->nullable();
            $table->date('tgl_pengajuan')->nullable();
            $table->date('exp_id')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_id');
    }
};
