<?php

namespace App\Imports;

use App\Models\Karyawan;
use App\Models\Mcu;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

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

            // Konversi exp_mcu
            $expMcu = null;
            if (!empty($row['exp_mcu'])) {
                if (is_numeric($row['exp_mcu'])) {
                    // Excel serial number -> Carbon
                    $expMcu = Date::excelToDateTimeObject($row['exp_mcu'])->format('Y-m-d');
                } else {
                    // Kalau sudah dalam string tanggal
                    $expMcu = date('Y-m-d', strtotime($row['exp_mcu']));
                }
            }

            $karyawan = Karyawan::updateOrCreate(
                [
                    'nik' => $row['nik'] ?? null,
                    'nrp' => $row['nrp'] ?? null,
                ],
                [
                    'nama'       => $row['nama'] ?? null,
                    'perusahaan' => $row['perusahaan'] ?? null,
                    'dept'       => $row['dept'] ?? null,
                    'jabatan'    => $row['jabatan'] ?? null,
                    'exp_id'     => $row['exp_id'] ?? null,
                    'exp_kimper' => $row['exp_kimper'] ?? null,
                ]
            );

            if (isset($row['exp_mcu']) && strtoupper(trim($row['exp_mcu'])) === 'OUT') {
                $karyawan->update([
                    'status'   => 'non aktif',
                    'exp_mcu'  => null,
                ]);
            } else {
                $karyawan->update([
                    'exp_mcu' => $expMcu,
                ]);
            }
            Mcu::where('nrp', $row['nrp'])
                ->update(['status_' => 'close']);
            return $karyawan;
        }

        return null;
    }
}
