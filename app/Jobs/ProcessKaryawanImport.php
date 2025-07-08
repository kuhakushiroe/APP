<?php

namespace App\Jobs;

use App\Models\Karyawan;
use App\Models\User;
use App\Models\VersatilityLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Hash;

class ProcessKaryawanImport implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $row;
    protected $unitList;

    /**
     * Create a new job instance.
     */
    public function __construct($row, $unitList)
    {
        //
        $this->row = $row;
        $this->unitList = $unitList;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        $row = $this->row;
        $unitList = $this->unitList;

        $nrp  = $row[5];
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

        $karyawan = Karyawan::where('nrp', $nrp)->first();
        $users = User::where('username', $nrp)->first();

        if ($karyawan) {
            $existing = array_filter(explode(',', $karyawan->versatility));
            $combined = array_unique(array_merge($existing, $newKendaraan));
            $added = array_diff($newKendaraan, $existing);

            if (!empty($added)) {
                VersatilityLog::create([
                    'nrp' => $nrp,
                    'kendaraan_baru' => implode(',', $added),
                    'updated_at' => now(),
                ]);
            }

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

            $users->update([
                'name' => $nama,
                'username' => $nrp,
                'email' => $nrp . '@example.com',
                'password' => Hash::make($nrp),
                'role' => 'karyawan',
            ]);
        } else {
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
            User::create([
                'name' => $nama,
                'username' => $nrp,
                'email' => $nrp . '@example.com',
                'password' => Hash::make($nrp),
                'role' => 'karyawan',
            ]);

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
