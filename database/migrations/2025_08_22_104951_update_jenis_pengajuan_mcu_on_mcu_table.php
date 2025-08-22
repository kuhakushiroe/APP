<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 2. Baru ubah definisi enum
        Schema::table('mcu', function (Blueprint $table) {
            $table->enum('jenis_pengajuan_mcu', [
                'Pre Employeed MCU',
                'Annual MCU',
                'MCU Khusus',
                'Exit MCU', // lama
                'Pre Employment',
                'Annual',
                'Temporary',
                'Khusus'            // baru
            ])->nullable()->change();
        });
        Schema::table('mcu', function (Blueprint $table) {
            $table->enum('tredmil_test', [
                'Hipertensif Positif',
                'Hipertensif Negatif',
                'Normal',
                'Normal Iskemik Respon',
                'Positive Iskemik Respon'
            ])->nullable()->change();
        });

        // Step 2: Update data lama ke data baru
        DB::table('mcu')
            ->where('jenis_pengajuan_mcu', 'Pre Employeed MCU')
            ->update(['jenis_pengajuan_mcu' => 'Pre Employment']);

        DB::table('mcu')
            ->where('jenis_pengajuan_mcu', 'Annual MCU')
            ->update(['jenis_pengajuan_mcu' => 'Annual']);

        DB::table('mcu')
            ->where('jenis_pengajuan_mcu', 'MCU Khusus')
            ->update(['jenis_pengajuan_mcu' => 'Khusus']);

        DB::table('mcu')
            ->where('jenis_pengajuan_mcu', 'Exit MCU')
            ->update(['jenis_pengajuan_mcu' => 'Temporary']);

        DB::table('mcu')
            ->where('tredmil_test', 'Hipertensif Positif')
            ->update(['tredmil_test' => 'Positive Iskemik Respon']);

        DB::table('mcu')
            ->where('tredmil_test', 'Hipertensif Negatif')
            ->update(['tredmil_test' => 'Normal']);

        DB::table('mcu')->whereIn('echocardiography', [
            'NORMAL',
            'Normal',
            'echocardiography. normal'
        ])->update(['echocardiography' => 'Normal']);

        DB::table('mcu')->whereIn('echocardiography', [
            'Tidak',
            'TIDAK DI LAKUKAN',
            'TIDAK DILAKUKAN',
            'TIDAK DILAKUHKAN'
        ])->update(['echocardiography' => 'Abnormal']);

        // Step 3: Sekarang aman, ubah definisi enum final
        Schema::table('mcu', function (Blueprint $table) {
            $table->enum('jenis_pengajuan_mcu', ['Pre Employment', 'Annual', 'Temporary', 'Khusus'])
                ->nullable()
                ->change();

            $table->enum('tredmil_test', ['Normal', 'Normal Iskemik Respon', 'Positive Iskemik Respon'])
                ->nullable()
                ->change();

            $table->enum('echocardiography', ['Normal', 'Abnormal'])
                ->nullable()
                ->change();

            $table->text('keterangan_mcu')->nullable()->change();
            $table->text('saran_mcu')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('mcu', function (Blueprint $table) {
            $table->enum('jenis_pengajuan_mcu', ['Pre Employeed MCU', 'Annual MCU', 'MCU Khusus', 'Exit MCU'])->nullable()->change();
            $table->enum('tredmil_test', ['Hipertensif Positif', 'Hipertensif Negatif'])
                ->nullable()
                ->change();
            $table->dropColumn('echocardiography'); // kalau awalnya memang belum ada
            $table->string('keterangan_mcu')->nullable()->change();
        });
    }
};
