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

class mcuExportDept implements FromQuery, WithHeadings, WithStyles, WithColumnFormatting
{
    protected $date_mcu1;
    protected $date_mcu2;
    protected $sub_role;

    public function __construct($date_mcu1 = null, $date_mcu2 = null, $sub_role = null)
    {
        $this->date_mcu1 = $date_mcu1;
        $this->date_mcu2 = $date_mcu2;
        $this->sub_role  = $sub_role;
    }

    public function query()
    {
        $query = DB::table('mcu')
            ->join('karyawans', 'karyawans.nrp', '=', 'mcu.id_karyawan')
            ->select(
                'karyawans.nrp',
                'karyawans.nama',
                'karyawans.dept',
                'karyawans.perusahaan',
                'mcu.status as hasil',
                'mcu.status_ as status'
            )
            ->orderBy('mcu.created_at', 'DESC');

        if ($this->sub_role) {
            $query->where('karyawans.dept', $this->sub_role);
        }

        if ($this->date_mcu1 && $this->date_mcu2) {
            $query->whereBetween('mcu.tgl_verifikasi', [$this->date_mcu1, $this->date_mcu2]);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'NRP',
            'Nama',
            'Dept',
            'Perusahaan',
            'Hasil',
            'Status',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($sheet->getHighestColumn());

        for ($col = 1; $col <= $highestColumnIndex; $col++) {
            $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
            $sheet->getColumnDimension($columnLetter)->setAutoSize(true);
        }

        $sheet->getStyle('1:1')->getFont()->setBold(true);

        return [];
    }

    public function columnFormats(): array
    {
        return [
            'A' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT,
        ];
    }
}
