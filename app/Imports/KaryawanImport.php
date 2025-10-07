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

            // Abaikan baris kosong
            if (empty($row['nrp']) || empty($row['nama'])) {
                return null;
            }

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
                    'nrp' => (string) $row['nrp'],
                ],
                [
                    'nama'       => (string) $row['nama'] ?? null,
                    'perusahaan' => (string) $row['perusahaan'] ?? null,
                    'dept'       => (string) $row['dept'] ?? null,
                    'jabatan'    => (string) $row['jabatan'] ?? null,
                    'exp_id'     => $row['exp_id'] ?? null,
                    'exp_kimper' => $row['exp_kimper'] ?? null,
                    'doh'        => $row['doh'] ?? now()
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
        }

        return null;
    }
}
