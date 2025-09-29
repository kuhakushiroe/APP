<?php

namespace App\Exports;

use App\Models\Karyawan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class KaryawansExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        if (auth()->user()->hasRole('superadmin')) {
            return Karyawan::select(
                'nik',
                'nrp',
                'tgl_lahir',
                'nama',
                'perusahaan',
                'dept',
                'jabatan',
                'doh',
                'no_hp',
                'alamat',
                'domisili',
                'status',
                'exp_mcu',
                'exp_id',
                'exp_kimper'
            )->get();
        } else {
            return Karyawan::select(
                'nik',
                'nrp',
                'tgl_lahir',
                'nama',
                'perusahaan',
                'dept',
                'jabatan',
                'doh',
                'no_hp',
                'alamat',
                'domisili',
                'status',
                'exp_mcu',
                'exp_id',
                'exp_kimper'
            )->where('dept', auth()->user()->subrole)->get();
        }
    }
    public function headings(): array
    {
        return [
            'nik',
            'nrp',
            'tgl_lahir',
            'nama',
            'perusahaan',
            'dept',
            'jabatan',
            'doh',
            'no_hp',
            'alamat',
            'domisili',
            'status',
            'exp_mcu',
            'exp_id',
            'exp_kimper'
        ];
    }
}
