<?php

namespace App\Exports;

use App\Models\Karyawan;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class mcuExp implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $today = Carbon::today();
        $oneMonthLater = Carbon::today()->addMonth();
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
            )
                ->where('status', 'aktif')
                ->where(function ($q) use ($today, $oneMonthLater) {
                    $q->where('exp_mcu', '<', $today)                    // expired
                        ->orWhereBetween('exp_mcu', [$today, $oneMonthLater]) // akan habis ≤ 1 bulan
                        ->orWhereNull('exp_mcu');                          // belum punya
                })
                ->orderByRaw('exp_mcu IS NULL') // NULL terakhir
                ->orderBy('exp_mcu', 'asc')
                ->get();
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
            )
                ->where('status', 'aktif')
                ->where('dept', auth()->user()->subrole)
                ->where(function ($q) use ($today, $oneMonthLater) {
                    $q->where('exp_mcu', '<', $today)                    // expired
                        ->orWhereBetween('exp_mcu', [$today, $oneMonthLater]) // akan habis ≤ 1 bulan
                        ->orWhereNull('exp_mcu');                          // belum punya
                })
                ->orderByRaw('exp_mcu IS NULL') // NULL terakhir
                ->orderBy('exp_mcu', 'asc')
                ->get();
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
        ];
    }
}
