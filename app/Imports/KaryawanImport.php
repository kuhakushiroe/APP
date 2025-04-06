<?php

namespace App\Imports;

use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class KaryawanImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        Karyawan::create([
            'foto' => $row['foto'],
            'nik' => $row['nik'],
            'nrp' => $row['nrp'],
            'tgl_lahir' => $row['tgl_lahir'],
            'nama' => $row['nama'],
            'jenis_kelamin' => $row['jenis_kelamin'],
            'tempat_lahir' => $row['tempat_lahir'],
            'agama' => $row['agama'],
            'gol_darah' => $row['gol_darah'],
            'status_perkawinan' => $row['status_perkawinan'],
            'perusahaan' => $row['perusahaan'],
            'kontraktor' => $row['kontraktor'],
            'dept' => $row['dept'],
            'jabatan' => $row['jabatan'],
            'no_hp' => $row['no_hp'],
            'alamat' => $row['alamat'],
            'domisili' => $row['domisili'],
            'status' => $row['status'],
        ]);
        User::create([
            'name' => $row['nama'],
            'username' => $row['nrp'],
            'email' => $row['nrp'] . '@example.com',
            'password' => Hash::make($row['nrp']),
            'role' => 'karyawan',
        ]);
    }
}
