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
        if (auth()->user()->hasRole('superadmin')) {
            Karyawan::create([
                'nik' => $row['nik'],
                'nrp' => $row['nrp'],
                'doh' => $row['doh'],
                'tgl_lahir' => $row['tgl_lahir'],
                'nama' => $row['nama'],
                'perusahaan' => $row['perusahaan'],
                'dept' => $row['dept'],
                'jabatan' => $row['jabatan'],
                'no_hp' => $row['no_hp'],
                'alamat' => $row['alamat'],
                'domisili' => $row['domisili'],
                'status' => $row['status'],
                'versatility' => $row['versatility'],
                'exp_id' => $row['exp_id'],
                'exp_kimper' => $row['exp_kimper'],
            ]);
        } else {
            Karyawan::create([
                'nik' => $row['nik'],
                'nrp' => $row['nrp'],
                'doh' => $row['doh'],
                'tgl_lahir' => $row['tgl_lahir'],
                'nama' => $row['nama'],
                'perusahaan' => $row['perusahaan'],
                'dept' => auth()->user()->subrole,
                'jabatan' => $row['jabatan'],
                'no_hp' => $row['no_hp'],
                'alamat' => $row['alamat'],
                'domisili' => $row['domisili'],
                'status' => $row['status'],
                'versatility' => $row['versatility'],
                'exp_id' => $row['exp_id'],
                'exp_kimper' => $row['exp_kimper'],
            ]);
        }
        User::create([
            'name' => $row['nama'],
            'username' => $row['nrp'],
            'email' => $row['nrp'] . '@example.com',
            'password' => Hash::make($row['nrp']),
            'role' => 'karyawan',
        ]);
    }
}
