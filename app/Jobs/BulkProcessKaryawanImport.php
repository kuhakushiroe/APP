<?php

namespace App\Jobs;

use App\Models\Karyawan;
use App\Models\User;
use App\Models\VersatilityLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Hash;

class BulkProcessKaryawanImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $rows;
    protected $unitList;

    public function __construct(array $rows, array $unitList)
    {
        $this->rows = $rows;
        $this->unitList = $unitList;
    }

    public function handle(): void
    {
        foreach ($this->rows as $row) {
            $nrp  = $row[5];
            $gol_darah = $row[6];
            $nama = $row[7];
            $perusahaan = $row[8];
            $kontraktor = $row[9];
            $dept = $row[10];
            $jabatan = $row[11];
            $nik = $row[12];
            $bpjs_kesehatan = isset($row[15]) ? (string) trim($row[15]) : null;
            $bpjs_tenagakerja = isset($row[16]) ? (string) trim($row[16]) : null;
            $status_karyawan = isset($row[102]) && trim($row[102]) !== ''
                ? strtoupper($row[102])
                : null;
            $newKendaraan = [];
            foreach ($this->unitList as $index => $unitName) {
                if (strtoupper($row[$index] ?? '') === 'F') {
                    $newKendaraan[] = strtoupper(str_replace('_', ' ', $unitName));
                }
            }

            $karyawan = Karyawan::firstWhere('nrp', $nrp);
            $user = User::firstWhere('username', $nrp);

            if ($karyawan) {
                $existing = array_filter(explode(',', $karyawan->versatility));
                $combined = array_unique(array_merge($existing, $newKendaraan));
                $added = array_diff($newKendaraan, $existing);

                if ($added) {
                    VersatilityLog::create([
                        'nrp' => $nrp,
                        'kendaraan_baru' => implode(',', $added),
                        'updated_at' => now(),
                    ]);
                }

                $karyawan->fill([
                    'nik' => $nik,
                    'nama' => $nama,
                    'gol_darah' => $gol_darah,
                    'perusahaan' => $perusahaan,
                    'kontraktor' => $kontraktor,
                    'dept' => $dept,
                    'jabatan' => $jabatan,
                    'versatility' => implode(',', $combined),
                    'bpjs_kesehatan' => $bpjs_kesehatan,
                    'bpjs_tenagakerja' => $bpjs_tenagakerja,
                    'status' => 'aktif',
                    'status_karyawan' => $status_karyawan,
                    'domisili' => 'lokal'
                ])->save();
            } else {
                $karyawan = Karyawan::create([
                    'nrp' => $nrp,
                    'nik' => $nik,
                    'nama' => $nama,
                    'gol_darah' => $gol_darah,
                    'perusahaan' => $perusahaan,
                    'kontraktor' => $kontraktor,
                    'dept' => $dept,
                    'jabatan' => $jabatan,
                    'versatility' => implode(',', $newKendaraan),
                    'doh' => now()->format('Y-m-d'),
                    'bpjs_kesehatan' => $bpjs_kesehatan,
                    'bpjs_tenagakerja' => $bpjs_tenagakerja,
                    'status' => 'aktif',
                    'status_karyawan' => $status_karyawan,
                    'domisili' => 'lokal'
                ]);

                if ($newKendaraan) {
                    VersatilityLog::create([
                        'nrp' => $nrp,
                        'kendaraan_baru' => implode(',', $newKendaraan),
                        'updated_at' => now(),
                    ]);
                }
            }

            // if ($user) {
            //     $user->update([
            //         'name' => $nama,
            //         'username' => $nrp,
            //         'email' => $nrp . '@example.com',
            //         'role' => 'karyawan',
            //     ]);
            // } else {
            //     User::create([
            //         'name' => $nama,
            //         'username' => $nrp,
            //         'email' => $nrp . '@example.com',
            //         'password' => Hash::make($nrp),
            //         'role' => 'karyawan',
            //     ]);
            // }
        }
    }
}
