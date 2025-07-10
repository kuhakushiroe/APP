<?php

namespace App\Imports;

use App\Jobs\ImportKaryawanJob;
use App\Jobs\ProcessKaryawanImport;
use App\Models\Karyawan;
use App\Models\User;
use App\Models\VersatilityLog;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;

class checklistImport implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    // {
    //     // Ambil row ke-2 untuk nama unit kendaraan
    //     $unitHeaderRow = $rows[2]; // index 1 = baris ke-2 Excel

    //     // Ambil nama kolom kendaraan mulai dari kolom ke-4 (index 3)
    //     $unitList = [];
    //     foreach ($unitHeaderRow as $key => $value) {
    //         if ($key >= 31 && !empty($value)) {
    //             $unitList[$key] = strtolower(str_replace(' ', '_', trim($value))); // Contoh: "Hino 500" => "hino_500"
    //         }
    //     }

    //     // Loop dari baris ke-3 ke bawah (index 2 dan seterusnya)
    //     for ($i = 3; $i < count($rows); $i++) {
    //         $row = $rows[$i];
    //         $nrp  = $row[5];
    //         $gol_darah = $row[6];
    //         $nama = $row[7];
    //         $perusahaan = $row[8];
    //         $kontraktor = $row[9];
    //         $dept = $row[10];
    //         $jabatan = $row[11];
    //         $nik = $row[12];

    //         // Deteksi kendaraan baru (bernilai "F")
    //         $kendaraan = [];
    //         foreach ($unitList as $index => $unitName) {
    //             if (strtoupper($row[$index] ?? '') === 'F') {
    //                 $kendaraan[] = strtoupper(str_replace('_', ' ', $unitName));
    //             }
    //         }

    //         $newKendaraan = array_map('strtoupper', $kendaraan);

    //         // Cari karyawan berdasarkan NRP
    //         $karyawan = Karyawan::where('nrp', $nrp)->first();
    //         $users = User::where('username', $nrp)->first();

    //         if ($karyawan) {
    //             // Ambil kendaraan lama
    //             $existing = array_filter(explode(',', $karyawan->versatility));

    //             // Gabungkan dan hilangkan duplikat
    //             $combined = array_unique(array_merge($existing, $newKendaraan));

    //             // Cari kendaraan yang baru saja ditambahkan
    //             $added = array_diff($newKendaraan, $existing);

    //             // Simpan log jika ada kendaraan baru
    //             if (!empty($added)) {
    //                 VersatilityLog::create([
    //                     'nrp' => $nrp,
    //                     'kendaraan_baru' => implode(',', $added),
    //                     'updated_at' => now(),
    //                 ]);
    //             }
    //             // Update karyawan
    //             $karyawan->update([
    //                 'nik' => $nik,
    //                 'nama' => $nama,
    //                 'gol_darah' => $gol_darah,
    //                 'perusahaan' => $perusahaan,
    //                 'kontraktor' => $kontraktor,
    //                 'dept' => $dept,
    //                 'jabatan' => $jabatan,
    //                 'versatility' => implode(',', $combined),
    //             ]);
    //             $users->update([
    //                 'name' => $nama,
    //                 'username' => $nrp,
    //                 'email' => $nrp . '@example.com',
    //                 'password' => Hash::make($nrp),
    //                 'role' => 'karyawan',
    //             ]);
    //         } else {
    //             // Buat karyawan baru
    //             Karyawan::create([
    //                 'nrp' => $nrp,
    //                 'nik' => $nik,
    //                 'nama' => $nama,
    //                 'gol_darah' => $gol_darah,
    //                 'perusahaan' => $perusahaan,
    //                 'kontraktor' => $kontraktor,
    //                 'dept' => $dept,
    //                 'jabatan' => $jabatan,
    //                 'doh' => now()->format('Y-m-d'),
    //                 'versatility' => implode(',', $newKendaraan),
    //             ]);
    //             User::create([
    //                 'name' => $nama,
    //                 'username' => $nrp,
    //                 'email' => $nrp . '@example.com',
    //                 'password' => Hash::make($nrp),
    //                 'role' => 'karyawan',
    //             ]);

    //             // Log kendaraan awal
    //             if (!empty($newKendaraan)) {
    //                 VersatilityLog::create([
    //                     'nrp' => $nrp,
    //                     'kendaraan_baru' => implode(',', $newKendaraan),
    //                     'updated_at' => now(),
    //                 ]);
    //             }
    //         }
    //     }
    // }
    // {
    //     $unitHeaderRow = $rows[2];

    //     $unitList = [];
    //     foreach ($unitHeaderRow as $key => $value) {
    //         if ($key >= 31 && !empty($value)) {
    //             $unitList[$key] = strtolower(str_replace(' ', '_', trim($value)));
    //         }
    //     }

    //     for ($i = 3; $i < count($rows); $i++) {
    //         $row = $rows[$i];
    //         // Dispatch setiap row ke queue
    //         ProcessKaryawanImport::dispatch($row, $unitList);
    //     }
    // }
    // {
    //     $unitHeaderRow = $rows[2];
    //     $unitList = [];

    //     foreach ($unitHeaderRow as $key => $value) {
    //         if ($key >= 31 && !empty($value)) {
    //             $unitList[$key] = strtolower(str_replace(' ', '_', trim($value)));
    //         }
    //     }

    //     $existingKaryawan = Karyawan::all()->keyBy('nrp');
    //     $existingUsers = User::all()->keyBy('username');

    //     $dataKaryawanInsert = [];
    //     $dataVersatilityLog = [];
    //     $dataUserInsert = [];

    //     foreach ($rows->slice(3) as $row) {
    //         $nrp  = $row[5];
    //         $gol_darah = $row[6];
    //         $nama = $row[7];
    //         $perusahaan = $row[8];
    //         $kontraktor = $row[9];
    //         $dept = $row[10];
    //         $jabatan = $row[11];
    //         $nik = $row[12];

    //         $kendaraan = [];
    //         foreach ($unitList as $index => $unitName) {
    //             if (strtoupper($row[$index] ?? '') === 'F') {
    //                 $kendaraan[] = strtoupper(str_replace('_', ' ', $unitName));
    //             }
    //         }
    //         $newKendaraan = array_map('strtoupper', $kendaraan);

    //         // === HANDLE KARYAWAN ===
    //         if ($existingKaryawan->has($nrp)) {
    //             $karyawan = $existingKaryawan[$nrp];
    //             $existing = array_filter(explode(',', $karyawan->versatility));
    //             $combined = array_unique(array_merge($existing, $newKendaraan));
    //             $added = array_diff($newKendaraan, $existing);

    //             $karyawan->update([
    //                 'nik' => $nik,
    //                 'nama' => $nama,
    //                 'gol_darah' => $gol_darah,
    //                 'perusahaan' => $perusahaan,
    //                 'kontraktor' => $kontraktor,
    //                 'dept' => $dept,
    //                 'jabatan' => $jabatan,
    //                 'versatility' => implode(',', $combined),
    //             ]);

    //             if (!empty($added)) {
    //                 $dataVersatilityLog[] = [
    //                     'nrp' => $nrp,
    //                     'kendaraan_baru' => implode(',', $added),
    //                     'created_at' => now(),
    //                     'updated_at' => now(),
    //                 ];
    //             }
    //         } else {
    //             $dataKaryawanInsert[] = [
    //                 'nrp' => $nrp,
    //                 'nik' => $nik,
    //                 'nama' => $nama,
    //                 'gol_darah' => $gol_darah,
    //                 'perusahaan' => $perusahaan,
    //                 'kontraktor' => $kontraktor,
    //                 'dept' => $dept,
    //                 'jabatan' => $jabatan,
    //                 'doh' => now()->toDateString(),
    //                 'versatility' => implode(',', $newKendaraan),
    //                 'created_at' => now(),
    //                 'updated_at' => now(),
    //             ];

    //             if (!empty($newKendaraan)) {
    //                 $dataVersatilityLog[] = [
    //                     'nrp' => $nrp,
    //                     'kendaraan_baru' => implode(',', $newKendaraan),
    //                     'created_at' => now(),
    //                     'updated_at' => now(),
    //                 ];
    //             }
    //         }

    //         // === HANDLE USER ===
    //         if ($existingUsers->has($nrp)) {
    //             $user = $existingUsers[$nrp];
    //             $user->update([
    //                 'name' => $nama,
    //                 'email' => $nrp . '@example.com',
    //                 'role' => 'karyawan',
    //             ]);
    //         } else {
    //             $dataUserInsert[] = [
    //                 'name' => $nama,
    //                 'username' => $nrp,
    //                 'email' => $nrp . '@example.com',
    //                 'password' => Hash::make($nrp),
    //                 'role' => 'karyawan',
    //                 'created_at' => now(),
    //                 'updated_at' => now(),
    //             ];
    //         }
    //     }

    //     // === INSERT MASSAL ===
    //     if (!empty($dataKaryawanInsert)) {
    //         Karyawan::insert($dataKaryawanInsert);
    //     }

    //     if (!empty($dataUserInsert)) {
    //         User::insert($dataUserInsert);
    //     }

    //     if (!empty($dataVersatilityLog)) {
    //         VersatilityLog::insert($dataVersatilityLog);
    //     }
    // }
    {
        ImportKaryawanJob::dispatch($rows->toArray());
    }
}
