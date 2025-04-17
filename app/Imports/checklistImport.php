<?php

namespace App\Imports;

use App\Models\Karyawan;
use App\Models\VersatilityLog;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class checklistImport implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        // Ambil row ke-2 untuk nama unit kendaraan
        $unitHeaderRow = $rows[2]; // index 1 = baris ke-2 Excel

        // Ambil nama kolom kendaraan mulai dari kolom ke-4 (index 3)
        $unitList = [];
        foreach ($unitHeaderRow as $key => $value) {
            if ($key >= 31 && !empty($value)) {
                $unitList[$key] = strtolower(str_replace(' ', '_', trim($value))); // Contoh: "Hino 500" => "hino_500"
            }
        }

        // Loop dari baris ke-3 ke bawah (index 2 dan seterusnya)
        for ($i = 3; $i < count($rows); $i++) {
            $row = $rows[$i];
            $nrp  = $row[5];
            $gol_darah = $row[6];
            $nama = $row[7];
            $perusahaan = $row[8];
            $kontraktor = $row[9];
            $dept = $row[10];
            $jabatan = $row[11];
            $nik = $row[12];

            // Deteksi kendaraan baru (bernilai "F")
            $kendaraan = [];
            foreach ($unitList as $index => $unitName) {
                if (strtoupper($row[$index] ?? '') === 'F') {
                    $kendaraan[] = strtoupper(str_replace('_', ' ', $unitName));
                }
            }

            $newKendaraan = array_map('strtoupper', $kendaraan);

            // Cari karyawan berdasarkan NRP
            $karyawan = Karyawan::where('nrp', $nrp)->first();

            if ($karyawan) {
                // Ambil kendaraan lama
                $existing = array_filter(explode(',', $karyawan->versatility));

                // Gabungkan dan hilangkan duplikat
                $combined = array_unique(array_merge($existing, $newKendaraan));

                // Cari kendaraan yang baru saja ditambahkan
                $added = array_diff($newKendaraan, $existing);

                // Simpan log jika ada kendaraan baru
                if (!empty($added)) {
                    VersatilityLog::create([
                        'nrp' => $nrp,
                        'kendaraan_baru' => implode(',', $added),
                        'updated_at' => now(),
                    ]);
                }
                // Update karyawan
                $karyawan->update([
                    'nik' => $nik,
                    'nama' => $nama,
                    'gol_darah' => $gol_darah,
                    'perusahaan' => $perusahaan,
                    'kontraktor' => $kontraktor,
                    'dept' => $dept,
                    'jabatan' => $jabatan,
                    'versatility' => implode(',', $combined),
                ]);
            } else {
                // Buat karyawan baru
                Karyawan::create([
                    'nrp' => $nrp,
                    'nik' => $nik,
                    'nama' => $nama,
                    'gol_darah' => $gol_darah,
                    'perusahaan' => $perusahaan,
                    'kontraktor' => $kontraktor,
                    'dept' => $dept,
                    'jabatan' => $jabatan,
                    'doh' => now()->format('Y-m-d'),
                    'versatility' => implode(',', $newKendaraan),
                ]);

                // Log kendaraan awal
                if (!empty($newKendaraan)) {
                    VersatilityLog::create([
                        'nrp' => $nrp,
                        'kendaraan_baru' => implode(',', $newKendaraan),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
