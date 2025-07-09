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
    public function query()
    {
        $latestMcuIds = DB::table('mcu')
            ->select(DB::raw('MAX(created_at) as latest_created_at'), 'id_karyawan')
            ->groupBy('id_karyawan');

        return ModelsMcu::join('karyawans', 'karyawans.nrp', '=', 'mcu.id_karyawan')
            ->joinSub($latestMcuIds, 'latest_mcus', function ($join) {
                $join->on('mcu.id_karyawan', '=', 'latest_mcus.id_karyawan')
                    ->on('mcu.created_at', '=', 'latest_mcus.latest_created_at');
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
                'mcu.catatan_file_mcu',
                'mcu.tgl_mcu',
                'mcu.tgl_verifikasi',
                'mcu.exp_mcu'
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
            'Tanggal MCU',
            'Tanggal Verifikasi',
            'Exp MCU'
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
