<?php

namespace App\Exports;

use App\Models\Karyawan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class checklistExport implements FromCollection, WithHeadings, WithEvents, WithStyles, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    private $unitList = [
        'HINO 500',
        'SCANIA P380',
        'MERCY 2528 RMC',
        'QUESTER',
        'IVECO 6824',
        'D85 SS',
        'D155 A',
        'D375 A',
        'PC200',
        'PC200 LA',
        'PC200 DF',
        'PC300',
        'PC400',
        'PC500',
        'PC850',
        'PC1250',
        'PC2000',
        'CAT 395 DL',
        'HD 465',
        'HD 785',
        'CAT 777/E'
    ];
    public function collection()
    {
        //
        return Karyawan::select(
            'id',
            'nrp',
            'nik',
            'nama',
            'versatility'
        )->get();
    }
    public function map($row): array
    {
        $versatility = array_map('trim', explode(',', $row->versatility));
        $unitFlags = [];

        foreach ($this->unitList as $unit) {
            $unitFlags[] = in_array($unit, $versatility) ? 'F' : '';
        }

        return array_merge([
            $row->no_reg_bantex,
            $row->nrp,
            $row->nik,
            $row->nama,
        ], $unitFlags);
    }

    public function headings(): array
    {
        return [
            [
                'NO. REG BANTEX',
                'NRP',
                'NIK',
                'NAMA',
                'WATER TRUCK',
                '',
                '',
                'FUEL TRUCK',
                '',
                'DOZER',
                '',
                '',
                '',
                'EXCAVATOR',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                'HEAVY DUTY TRUCK',
                '',
                ''
            ],
            [
                '',
                '',
                '',
                '',
                'HINO 500',
                'SCANIA P380',
                'MERCY 2528 RMC',
                'QUESTER',
                'IVECO 6824',
                'D85 SS',
                'D155 A',
                'D375 A',
                'PC200',
                'PC200 LA',
                'PC200 DF',
                'PC300',
                'PC400',
                'PC500',
                'PC850',
                'PC1250',
                'PC2000',
                'CAT 395 DL',
                'HD 465',
                'HD 785',
                'CAT 777/E'
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                // Merge headers
                $sheet->mergeCells('A1:A2'); // NO. REG
                $sheet->mergeCells('B1:B2'); // NRP
                $sheet->mergeCells('C1:C2'); // NIK
                $sheet->mergeCells('D1:D2'); // NAMA

                $sheet->mergeCells('E1:G1');  // WATER TRUCK
                $sheet->mergeCells('H1:I1');  // FUEL TRUCK
                $sheet->mergeCells('J1:M1');  // DOZER
                $sheet->mergeCells('N1:W1');  // EXCAVATOR
                $sheet->mergeCells('X1:Z1');  // HEAVY DUTY TRUCK

                // Styling
                $sheet->getStyle('A1:Z2')->getFont()->setBold(true);
                $sheet->getStyle('A1:Z2')->getAlignment()->setHorizontal('center');
                $sheet->getStyle('A1:Z2')->getAlignment()->setVertical('center');
                $sheet->getRowDimension(1)->setRowHeight(25);
                $sheet->getRowDimension(2)->setRowHeight(25);
            }
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            2 => ['font' => ['bold' => true]],
        ];
    }
}
