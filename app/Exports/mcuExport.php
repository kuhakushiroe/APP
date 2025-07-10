<?php

namespace App\Exports;

use App\Livewire\Pengajuan\Mcu;
use App\Models\Mcu as ModelsMcu;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class mcuExport implements FromQuery, WithHeadings, WithStyles, WithColumnFormatting
{
    /**
     * @return \Illuminate\Support\Collection
     */
    // public function query()
    // {
    //     $latestMcuIds = DB::table('mcu')
    //         ->select(DB::raw('MAX(created_at) as latest_created_at'), 'id_karyawan')
    //         ->groupBy('id_karyawan');

    //     return ModelsMcu::join('karyawans', 'karyawans.nrp', '=', 'mcu.id_karyawan')
    //         ->joinSub($latestMcuIds, 'latest_mcus', function ($join) {
    //             $join->on('mcu.id_karyawan', '=', 'latest_mcus.id_karyawan')
    //                 ->on('mcu.created_at', '=', 'latest_mcus.latest_created_at');
    //         })
    //         ->select(
    //             'karyawans.nrp',
    //             DB::raw("CONCAT('\'', karyawans.nik) as nik"),
    //             'karyawans.nama',
    //             DB::raw("TIMESTAMPDIFF(YEAR, karyawans.tgl_lahir, CURDATE()) as umur"),
    //             'karyawans.jenis_kelamin',
    //             'karyawans.perusahaan',
    //             'karyawans.dept',
    //             'karyawans.jabatan',
    //             'mcu.proveder',
    //             'mcu.status',
    //             'mcu.keterangan_mcu',
    //             'mcu.saran_mcu',
    //             'mcu.tgl_mcu',
    //             'mcu.tgl_verifikasi',
    //             'mcu.exp_mcu'
    //         )
    //         ->orderBy('mcu.created_at', 'DESC')
    //         ->orderBy('mcu.status', 'DESC');
    // }
    public function query()
    {
        // Subquery untuk MCU terbaru per karyawan
        $latestMcu = DB::table('mcu')
            ->select(DB::raw('MAX(created_at) as latest_created_at'), 'id_karyawan')
            ->groupBy('id_karyawan');

        return DB::table('karyawans')
            ->leftJoinSub($latestMcu, 'latest_mcu', function ($join) {
                $join->on('karyawans.nrp', '=', 'latest_mcu.id_karyawan');
            })
            ->leftJoin('mcu', function ($join) {
                $join->on('karyawans.nrp', '=', 'mcu.id_karyawan')
                    ->on('mcu.created_at', '=', DB::raw('latest_mcu.latest_created_at'));
            })
            ->select(
                'karyawans.nrp',
                DB::raw("CONCAT('\'', karyawans.nik) as nik"),
                'karyawans.nama',
                DB::raw("TIMESTAMPDIFF(YEAR, karyawans.tgl_lahir, CURDATE()) as umur"),
                'karyawans.jenis_kelamin',
                'karyawans.perusahaan',
                'karyawans.dept',
                'karyawans.jabatan',
                'mcu.proveder',
                'mcu.status',
                'mcu.keterangan_mcu',
                'mcu.saran_mcu',
                'mcu.tgl_mcu',
                'mcu.tgl_verifikasi',
                'mcu.verifikator',
                'mcu.exp_mcu',

                'mcu.riwayat_rokok',
                'mcu.BB',
                'mcu.TB',
                'mcu.LP',
                'mcu.BMI',
                'mcu.Laseq',
                'mcu.reqtal_touche',
                'mcu.sistol',
                'mcu.diastol',
                'mcu.OD_jauh',
                'mcu.OS_jauh',
                'mcu.OD_dekat',
                'mcu.OS_dekat',
                'mcu.butawarna',
                'mcu.gdp',
                'mcu.gd_2_jpp',
                'mcu.hba1c',
                'mcu.ureum',
                'mcu.creatine',
                'mcu.asamurat',
                'mcu.sgot',
                'mcu.sgpt',
                'mcu.gamma_gt',
                'mcu.hbsag',
                'mcu.kolesterol',
                'mcu.hdl',
                'mcu.ldl',
                'mcu.tg',
                'mcu.napza',
                'mcu.urin',
                'mcu.ekg',
                'mcu.rontgen',
                'mcu.audiometri',
                'mcu.spirometri',
                'mcu.tredmil_test',
                'mcu.widal_test',
                'mcu.routin_feces',
                'mcu.kultur_feces',
                'mcu.anti_hbs',
                'mcu.darah_rutin',
                'mcu.status_',
                'mcu.temuan',
                'mcu.keterangan_mcu',
                'mcu.saran_mcu'
            )
            ->orderBy('mcu.created_at', 'DESC')
            ->orderBy('mcu.status', 'DESC');
    }


    public function headings(): array
    {
        return [
            'NRP',
            'NIK',
            'Nama',
            'Umur',
            'Jk',
            'Perusahaan',
            'Dept',
            'Jabatan',
            'MCU Provider',
            'Hasil MCU',
            'Catatan',
            'Saran',
            'Tanggal MCU',
            'Tanggal Verifikasi',
            'Verifikator',
            'Exp MCU',

            'Riwayat Rokok',
            'Berat Badan (BB)',
            'Tinggi Badan (TB)',
            'Lingkar Perut (LP)',
            'BMI',
            'Laseq',
            'Rectal Touche',
            'Sistol',
            'Diastol',
            'Mata OD Jauh',
            'Mata OS Jauh',
            'Mata OD Dekat',
            'Mata OS Dekat',
            'Buta Warna',
            'GDP',
            'GD 2JPP',
            'HbA1C',
            'Ureum',
            'Creatinine',
            'Asam Urat',
            'SGOT',
            'SGPT',
            'Gamma GT',
            'HbsAg',
            'Kolesterol',
            'HDL',
            'LDL',
            'Trigliserida (TG)',
            'NAPZA',
            'Urin',
            'EKG',
            'Rontgen',
            'Audiometri',
            'Spirometri',
            'Treadmill Test',
            'Widal Test',
            'Routin Feces',
            'Kultur Feces',
            'Anti HBs',
            'Darah Rutin',
            'Status MCU',
            'Temuan',
            'Keterangan MCU',
            'Saran MCU'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        foreach (range('A', $sheet->getHighestColumn()) as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Optionally, apply other styles like header styles here
        $sheet->getStyle('1:1')->getFont()->setBold(true);

        return [
            // Additional styling options can be set here
        ];
    }
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT, // Assuming 'A' is the column for NIK
        ];
    }
}
