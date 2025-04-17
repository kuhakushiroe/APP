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
        'LV',
        'ELF',
        'BUS',
        'CRANE',
        'LOADER KOM 480 500',
        'SANY STC 750',
        'SANY STC 60T',
        'ISUZU ELF HYVA/HB60E2',
        'HINO 260 Ti',
        'HINO 500',
        'SCANIA P380',
        'NISSAN',
        'FUSO',
        'TADANO 50 T',
        'BOMAG 211 D 40',
        'SAKAI SV 526',
        'HINO 260 Ti',
        'MERCY 2528 RMC',
        'HINO 500',
        'HINO 500',
        'SCANIA P380',
        'MERCY 2528 RMC',
        'QUESTER',
        'IVECO 6824',
        'MERCY 2528 RMC',
        'HINO 500',
        'D 85 SS',
        'D 155 A',
        'D 375 A',
        'PC 200',
        'PC 200 LA',
        'PC 200 DF',
        'PC 300',
        'PC 400',
        'PC 500',
        'PC 850',
        'PC1250',
        'PC2000',
        'CAT 395 DL',
        'HD 465',
        'HD 785',
        'CAT 777E',
        'ARTICULATED DT A 40 G',
        'DT VOLVO FMX 400(OB)',
        'DT VOLVO FMX 440(COAL)',
        'DT SCANIA P 360 (OB)',
        'DT SCANIA P460 (COAL)',
        'DT SCANIA P 410 (COAL)',
        'DT NISSAN CWB',
        'DT MERCY 4040 K',
        'DT MERCY 4845 K',
        'DT QUESTER',
        'MG 511',
        'MG 705 A',
        'MG 825 A',
        'MG 755-5R',
        'Forklift FD 50X',
        'MERLO 27-10',
        'MANITOU MHT-X 860L',
        'DT QUESTER',
        'LOWBOY SCANIA R580',
        'DRILLING MACHINE 254 KS',
        'DRILLING MACHINE 245KS',
    ];
    public function collection()
    {
        //
        return Karyawan::all();
    }
    public function map($row): array
    {
        $versatility = array_map('trim', explode(',', $row->versatility));
        $unitFlags = [];

        foreach ($this->unitList as $unit) {
            $unitFlags[] = in_array($unit, $versatility) ? 'F' : '';
        }

        return array_merge([
            '-',
            '-',
            '-',
            '-',
            '-',
            $row->nrp,
            $row->gol_darah,
            $row->nama, // nama
            $row->perusahaan,
            $row->kontraktor,
            $row->dept,
            $row->jabatan,
            $row->nik,
            '-',
            '-',
            '-',
            '-',
            '-',
            '-',
            '-',
            '-',
            '-',
            '-',
            '-',
            '-',
            '-',
            '-',
            '-',
            '-',
            '-',
            '-',
            '-',
        ], $unitFlags);
    }

    public function headings(): array
    {
        return [
            [
                'NO. REG BANTEX',
                'NO. REG BANTEX',
                'NO',
                'PENGAJUAN INDUKSI KARYAWAN',
                'NOMOR REGISTER KIMPER',
                'NRP / NIK',
                'GOLONGAN DARAH',
                'NAMA KARYAWAN',
                'PERUSAHAAN',
                'PERUSAHAAN MITRA KERJA',
                'DEPARTEMEN',
                'JABATAN',
                'NO INDUK KTP',
                'TGL INDUKSI', //merge 3
                'KELENGKAPAN ADMINISTRASI DEPARTEMEN HCGA',
                '', // KELENGKAPAN ADMINISTRASI DEPARTEMEN HCGA
                '', // KELENGKAPAN ADMINISTRASI DEPARTEMEN HCGA
                '', // KELENGKAPAN ADMINISTRASI DEPARTEMEN HCGA
                '', // KELENGKAPAN ADMINISTRASI DEPARTEMEN HCGA //merge 2 //ID CARD	BPJS KESEHATAN	BPJS KETENAGAKERJAAN	SURAT KETERANGAN DOKTER	VAKSIN COVID
                'KELENGKAPAN ADMINISTRASI DEPARTEMEN ACADEMY', //merge 2 //'FORM PERMOHONAN KIMPER'	'NILAI TES RAMBU'	'NILAI TES TEORI'	'NILAI TES P2H'	'NILAI TES PRAKTEK'	'( DDT ) DEFENSIVE DRIVING TRAINING'	'HASIL EVALUASI TEST KIMPER'
                '', // 'KELENGKAPAN ADMINISTRASI DEPARTEMEN ACADEMY'
                '', // 'KELENGKAPAN ADMINISTRASI DEPARTEMEN ACADEMY'
                '', // 'KELENGKAPAN ADMINISTRASI DEPARTEMEN ACADEMY'
                '', // 'KELENGKAPAN ADMINISTRASI DEPARTEMEN ACADEMY'
                '', // 'KELENGKAPAN ADMINISTRASI DEPARTEMEN ACADEMY'
                '', // 'KELENGKAPAN ADMINISTRASI DEPARTEMEN ACADEMY'
                'KELENGKAPAN ADMINISTRASI DEPARTEMEN HCGA', //'LAMPIRAN PENGALAMAN KERJA / TRAINING BLASTING
                'KLASIFIKASI', //MINE PERMIT ATAU MINE LICENSE
                'DATA MINE LICENSE',
                '', // DATA MINE LICENSE
                '', // DATA MINE LICENSE //'JENIS SIM POLISI'	'NOMOR SIM POLISI'	'MASA EXPIRED SIM POLISI'
                'DATA MINE PERMIT', //'MASA EXPIRED MCU'
                'UNIT YANG DI OPERASIKAN',
                '', // GENERAL SUPPORT SARANA
                '', // GENERAL SUPPORT SARANA
                '', // GENERAL SUPPORT SARANA
                '', // OTHERS
                '', // OTHERS
                '', // TRUCK CRANE
                '', // TRUCK CRANE
                '', // TRUCK CRANE
                '', // TRUCK CRANE
                '', // TRUCK CRANE
                '', // TRUCK CRANE
                '', // TRUCK CRANE
                '', // TRUCK CRANE
                '', // TRUCK CRANE
                '', // COMPACTOR
                '', // COMPACTOR
                '', // LUBE TRUCK
                '', // LUBE TRUCK
                '', // LUBE TRUCK
                '', // WATER TRUCK
                '', // WATER TRUCK
                '', // WATER TRUCK
                '', // FUEL TRUCK
                '', // FUEL TRUCK
                '', // FUEL TRUCK
                '', // DOZER
                '', // DOZER
                '', // DOZER
                '', // EXCAVATOR
                '', // EXCAVATOR
                '', // EXCAVATOR
                '', // EXCAVATOR
                '', // EXCAVATOR
                '', // EXCAVATOR
                '', // EXCAVATOR
                '', // EXCAVATOR
                '', // EXCAVATOR
                '', // EXCAVATOR
                '', // HEAVY DUTY TRUCK
                '', // HEAVY DUTY TRUCK
                '', // HEAVY DUTY TRUCK
                '', // DUMP TRUCK / ARTICULATED DUMP TRUCK
                '', // DUMP TRUCK / ARTICULATED DUMP TRUCK
                '', // DUMP TRUCK / ARTICULATED DUMP TRUCK
                '', // DUMP TRUCK / ARTICULATED DUMP TRUCK
                '', // DUMP TRUCK / ARTICULATED DUMP TRUCK
                '', // DUMP TRUCK / ARTICULATED DUMP TRUCK
                '', // DUMP TRUCK / ARTICULATED DUMP TRUCK
                '', // DUMP TRUCK / ARTICULATED DUMP TRUCK
                '', // DUMP TRUCK / ARTICULATED DUMP TRUCK
                '', // DUMP TRUCK / ARTICULATED DUMP TRUCK
                '', // MOTOR GRADE
                '', // MOTOR GRADE
                '', // MOTOR GRADE
                '', // MOTOR GRADE
                '', // OTHERS
                '', // OTHERS
                '', // OTHERS
                '', // OTHERS
                '', // OTHERS
                '', // OTHERS
                '', // OTHERS
                'TANGGAL PENCETAKAN',
                'PENERIMA',
                'MASA AKTIF SIMPER',
                'MASA AKTIF PERMIT',
                'KETERANGAN DONE, PROGRESS, BELUM ADA BERKAS',
                'NILAI PENENTU',
                'NILAI PENENTU PERUSAHAAN',
                'STATUS KARYAWAN',
                'MONITORING PERALIHAN',
                'KETERANGAN BANTEX, OPEN, CLOSE'
            ],
            [
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '', // KELENGKAPAN ADMINISTRASI DEPARTEMEN HCGA
                '', // KELENGKAPAN ADMINISTRASI DEPARTEMEN HCGA
                '', // KELENGKAPAN ADMINISTRASI DEPARTEMEN HCGA
                '', // KELENGKAPAN ADMINISTRASI DEPARTEMEN HCGA
                '', // KELENGKAPAN ADMINISTRASI DEPARTEMEN HCGA
                '', // 'KELENGKAPAN ADMINISTRASI DEPARTEMEN ACADEMY'
                '', // 'KELENGKAPAN ADMINISTRASI DEPARTEMEN ACADEMY'
                '', // 'KELENGKAPAN ADMINISTRASI DEPARTEMEN ACADEMY'
                '', // 'KELENGKAPAN ADMINISTRASI DEPARTEMEN ACADEMY'
                '', // 'KELENGKAPAN ADMINISTRASI DEPARTEMEN ACADEMY'
                '', // 'KELENGKAPAN ADMINISTRASI DEPARTEMEN ACADEMY'
                '', // 'KELENGKAPAN ADMINISTRASI DEPARTEMEN ACADEMY'
                '', // KELENGKAPAN ADMINISTRASI DEPARTEMEN HCGA
                '', // KLASIFIKASI
                '', // DATA MINE LICENSE
                '', // DATA MINE LICENSE
                '', // DATA MINE LICENSE
                '', // DATA MINE PERMIT
                'GENERAL SUPPORT SARANA',
                '', // GENERAL SUPPORT SARANA
                '', // GENERAL SUPPORT SARANA
                'OTHERS',
                '', // OTHERS
                'TRUCK CRANE',
                '', // TRUCK CRANE
                '', // TRUCK CRANE
                '', // TRUCK CRANE
                '', // TRUCK CRANE
                '', // TRUCK CRANE
                '', // TRUCK CRANE
                '', // TRUCK CRANE
                '', // TRUCK CRANE
                'COMPACTOR',
                '', // COMPACTOR
                'LUBE TRUCK',
                '', // LUBE TRUCK
                '', // LUBE TRUCK
                'WATER TRUCK',
                '', // WATER TRUCK
                '', // WATER TRUCK
                'FUEL TRUCK',
                '', // FUEL TRUCK
                '', // FUEL TRUCK
                '', // FUEL TRUCK
                'DOZER',
                '', // DOZER
                '', // DOZER
                'EXCAVATOR',
                '', // EXCAVATOR
                '', // EXCAVATOR
                '', // EXCAVATOR
                '', // EXCAVATOR
                '', // EXCAVATOR
                '', // EXCAVATOR
                '', // EXCAVATOR
                '', // EXCAVATOR
                '', // EXCAVATOR
                'HEAVY DUTY TRUCK',
                '', // HEAVY DUTY TRUCK
                '', // HEAVY DUTY TRUCK
                'DUMP TRUCK / ARTICULATED DUMP TRUCK',
                '', // DUMP TRUCK / ARTICULATED DUMP TRUCK
                '', // DUMP TRUCK / ARTICULATED DUMP TRUCK
                '', // DUMP TRUCK / ARTICULATED DUMP TRUCK
                '', // DUMP TRUCK / ARTICULATED DUMP TRUCK
                '', // DUMP TRUCK / ARTICULATED DUMP TRUCK
                '', // DUMP TRUCK / ARTICULATED DUMP TRUCK
                '', // DUMP TRUCK / ARTICULATED DUMP TRUCK
                '', // DUMP TRUCK / ARTICULATED DUMP TRUCK
                '', // DUMP TRUCK / ARTICULATED DUMP TRUCK
                'MOTOR GRADE',
                '', // MOTOR GRADE
                '', // MOTOR GRADE
                '', // MOTOR GRADE
                'OTHERS',
                '', // OTHERS
                '', // OTHERS
                '', // OTHERS
                '', // OTHERS
                '', // OTHERS
                '', // OTHERS
                '', // sisa 10
                '', // sisa 10
                '', // sisa 10
                '', // sisa 10
                '', // sisa 10
                '', // sisa 10
                '', // sisa 10
                '', // sisa 10
                '', // sisa 10
                '', // sisa 10
            ],
            [
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                'ID CARD',
                'BPJS KESEHATAN',
                'BPJS KETENAGAKERJAAN',
                'SURAT KETERANGAN DOKTER',
                'VAKSIN COVID',

                'FORM PERMOHONAN KIMPER',
                'NILAI TES RAMBU',
                'NILAI TES TEORI',
                'NILAI TES P2H',
                'NILAI TES PRAKTEK',
                '( DDT ) DEFENSIVE DRIVING TRAINING',
                'HASIL EVALUASI TEST KIMPER',


                'LAMPIRAN PENGALAMAN KERJA / TRAINING BLASTING',
                'MINE PERMIT ATAU MINE LICENSE',
                'JENIS SIM POLISI',
                'NOMOR SIM POLISI',
                'MASA EXPIRED SIM POLISI',
                'MASA EXPIRED MCU',
                'LV',
                'ELF',
                'BUS',
                'CRANE',
                'LOADER KOM 480 500',
                'SANY STC 750',
                'SANY STC 60T',
                'ISUZU ELF HYVA/HB60E2',
                'HINO 260 Ti',
                'HINO 500',
                'SCANIA P380',
                'NISSAN',
                'FUSO',
                'TADANO 50 T',
                'BOMAG 211 D 40',
                'SAKAI SV 526',
                'HINO 260 Ti',
                'MERCY 2528 RMC',
                'HINO 500',
                'HINO 500',
                'SCANIA P380',
                'MERCY 2528 RMC',
                'QUESTER',
                'IVECO 6824',
                'MERCY 2528 RMC',
                'HINO 500',
                'D 85 SS',
                'D 155 A',
                'D 375 A',
                'PC 200',
                'PC 200 LA',
                'PC 200 DF',
                'PC 300',
                'PC 400',
                'PC 500',
                'PC 850',
                'PC1250',
                'PC2000',
                'CAT 395 DL',
                'HD 465',
                'HD 785',
                'CAT 777E',
                'ARTICULATED DT A 40 G',
                'DT VOLVO FMX 400(OB)',
                'DT VOLVO FMX 440(COAL)',
                'DT SCANIA P 360 (OB)',
                'DT SCANIA P460 (COAL)',
                'DT SCANIA P 410 (COAL)',
                'DT NISSAN CWB',
                'DT MERCY 4040 K',
                'DT MERCY 4845 K',
                'DT QUESTER',
                'MG 511',
                'MG 705 A',
                'MG 825 A',
                'MG 755-5R',
                'Forklift FD 50X',
                'MERLO 27-10',
                'MANITOU MHT-X 860L',
                'DT QUESTER',
                'LOWBOY SCANIA R580',
                'DRILLING MACHINE 254 KS',
                'DRILLING MACHINE 245KS',
                '', // sisa 10
                '', // sisa 10
                '', // sisa 10
                '', // sisa 10
                '', // sisa 10
                '', // sisa 10
                '', // sisa 10
                '', // sisa 10
                '', // sisa 10
                '', // sisa 10
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                //Merge headers
                $sheet->mergeCells('A1:A3'); // NO. REG
                $sheet->mergeCells('B1:B3'); // NRP
                $sheet->mergeCells('C1:C3'); // NIK
                $sheet->mergeCells('D1:D3'); // NAMA
                $sheet->mergeCells('E1:E3'); // NO. REG
                $sheet->mergeCells('F1:F3'); // NRP
                $sheet->mergeCells('G1:G3'); // NIK
                $sheet->mergeCells('H1:H3'); // NAMA
                $sheet->mergeCells('I1:I3'); // NO. REG
                $sheet->mergeCells('J1:J3'); // NRP
                $sheet->mergeCells('K1:K3'); // NIK
                $sheet->mergeCells('L1:L3'); // NIK
                $sheet->mergeCells('M1:M3'); // NIK
                $sheet->mergeCells('N1:N3'); // NIK

                $sheet->mergeCells('O1:S2'); // NIK
                $sheet->mergeCells('T1:Z2'); // NIK
                $sheet->mergeCells('AA1:AA2'); // NIK
                $sheet->mergeCells('AB1:AB2'); // NIK
                $sheet->mergeCells('AC1:AE2'); // NIK
                $sheet->mergeCells('AF1:AF2'); // NIK

                $sheet->mergeCells('AG1:CQ1');
                $sheet->mergeCells('AG2:AI2');
                $sheet->mergeCells('AJ2:AK2');
                $sheet->mergeCells('AL2:AT2');
                $sheet->mergeCells('AU2:AV2');
                $sheet->mergeCells('AW2:AY2');
                $sheet->mergeCells('AZ2:BB2');
                $sheet->mergeCells('BC2:BF2');
                $sheet->mergeCells('BG2:BI2');
                $sheet->mergeCells('BJ2:BS2');
                $sheet->mergeCells('BT2:BV2');
                $sheet->mergeCells('BW2:CF2');
                $sheet->mergeCells('CG2:CJ2');
                $sheet->mergeCells('CK2:CQ2');
                $sheet->mergeCells('CR1:CR3');
                $sheet->mergeCells('CS1:CS3');
                $sheet->mergeCells('CT1:CT3');
                $sheet->mergeCells('CU1:CU3');
                $sheet->mergeCells('CV1:CV3');
                $sheet->mergeCells('CW1:CW3');
                $sheet->mergeCells('CX1:CX3');
                $sheet->mergeCells('CY1:CY3');
                $sheet->mergeCells('CZ1:CZ3');
                $sheet->mergeCells('DA1:DA3');

                // Styling
                $sheet->getStyle('A1:DA3')->getFont()->setBold(true);
                $sheet->getStyle('A1:DA3')->getAlignment()->setHorizontal('center');
                $sheet->getStyle('A1:DA3')->getAlignment()->setVertical('center');
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
