<?php

namespace App\Jobs;

use App\Models\Karyawan;
use App\Models\User;
use App\Models\VersatilityLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Hash;

class ImportKaryawanJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $rows;

    /**
     * Create a new job instance.
     */
    public function __construct($rows)
    {
        //
        $this->rows = $rows;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        $rows = collect($this->rows);
        $unitHeaderRow = $rows[2];
        $unitList = [];

        foreach ($unitHeaderRow as $key => $value) {
            if ($key >= 31 && !empty($value)) {
                $unitList[$key] = strtolower(str_replace(' ', '_', trim($value)));
            }
        }

        $existingKaryawan = Karyawan::all()->keyBy('nrp');
        $existingUsers = User::all()->keyBy('username');

        $dataKaryawanInsert = [];
        $dataVersatilityLog = [];
        $dataUserInsert = [];

        foreach ($rows->slice(3) as $row) {
            $nrp = $row[5];
            $gol_darah = $row[6];
            $nama = $row[7];
            $perusahaan = $row[8];
            $kontraktor = $row[9];
            $dept = $row[10];
            $jabatan = $row[11];
            $nik = $row[12];

            $kendaraan = [];
            foreach ($unitList as $index => $unitName) {
                if (strtoupper($row[$index] ?? '') === 'F') {
                    $kendaraan[] = strtoupper(str_replace('_', ' ', $unitName));
                }
            }
            $newKendaraan = array_map('strtoupper', $kendaraan);

            if ($existingKaryawan->has($nrp)) {
                $karyawan = $existingKaryawan[$nrp];
                $existing = array_filter(explode(',', $karyawan->versatility));
                $combined = array_unique(array_merge($existing, $newKendaraan));
                $added = array_diff($newKendaraan, $existing);

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

                if (!empty($added)) {
                    $dataVersatilityLog[] = [
                        'nrp' => $nrp,
                        'kendaraan_baru' => implode(',', $added),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            } else {
                $dataKaryawanInsert[] = [
                    'nrp' => $nrp,
                    'nik' => $nik,
                    'nama' => $nama,
                    'gol_darah' => $gol_darah,
                    'perusahaan' => $perusahaan,
                    'kontraktor' => $kontraktor,
                    'dept' => $dept,
                    'jabatan' => $jabatan,
                    'doh' => now()->toDateString(),
                    'versatility' => implode(',', $newKendaraan),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if (!empty($newKendaraan)) {
                    $dataVersatilityLog[] = [
                        'nrp' => $nrp,
                        'kendaraan_baru' => implode(',', $newKendaraan),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            if ($existingUsers->has($nrp)) {
                $user = $existingUsers[$nrp];
                $user->update([
                    'name' => $nama,
                    'email' => $nrp . '@example.com',
                    'role' => 'karyawan',
                ]);
            } else {
                $dataUserInsert[] = [
                    'name' => $nama,
                    'username' => $nrp,
                    'email' => $nrp . '@example.com',
                    'password' => Hash::make($nrp),
                    'role' => 'karyawan',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if (!empty($dataKaryawanInsert)) {
            Karyawan::insert($dataKaryawanInsert);
        }

        if (!empty($dataUserInsert)) {
            User::insert($dataUserInsert);
        }

        if (!empty($dataVersatilityLog)) {
            VersatilityLog::insert($dataVersatilityLog);
        }
    }
}
