<?php

namespace App\Livewire;

use App\Exports\mcuExport;
use App\Exports\mcuExportDept;
use Livewire\Attributes\Title;
use Livewire\Component;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Facades\Excel as FacadesExcel;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class Report extends Component
{
    #[Title('Report')]
    public $date_mcu1, $date_mcu2;
    public $date_id1, $date_id2;
    public $date_kimper1, $date_kimper2;
    public function mount()
    {
        $this->date_mcu1 = date('Y-m-d');
        $this->date_mcu2 = date('Y-m-d');
        $this->date_id1 = date('Y-m-d');
        $this->date_id2 = date('Y-m-d');
        $this->date_kimper1 = date('Y-m-d');
        $this->date_kimper2 = date('Y-m-d');
    }
    public function mcuReport($date_mcu1, $date_mcu2)
    {
        $role = auth()->user()->role;
        $sub_role = auth()->user()->subrole;
        $filename = 'Register_MCU_' . $date_mcu1 . '_' . $date_mcu2 . '.xlsx';
        if ($role === 'pimpinan') {
            return FacadesExcel::download(new mcuExportDept($date_mcu1, $date_mcu2, $sub_role), $filename);
        } else {
            return FacadesExcel::download(new mcuExport($date_mcu1, $date_mcu2), $filename);
        }
    }

    public function reportId($date1, $date2)
    {
        // $data = ModelPengajuanID::whereBetween('tgl_mcu', [$date1, $date2])->get();
        if (auth()->user()->role === 'pimpinan') {
            $data = DB::table('pengajuan_id')
                ->join('karyawans', 'pengajuan_id.nrp', '=', 'karyawans.nrp')
                ->select(
                    'karyawans.nama',
                    'karyawans.nik',
                    'karyawans.nrp',
                    'karyawans.jabatan',
                    'karyawans.dept',
                    'karyawans.perusahaan',
                    'karyawans.doh',
                    'pengajuan_id.upload_foto',
                    'pengajuan_id.upload_ktp',
                    'pengajuan_id.upload_skd',
                    'pengajuan_id.tgl_pengajuan',
                    'pengajuan_id.exp_id',
                    'pengajuan_id.jenis_pengajuan_id',
                    'pengajuan_id.status_pengajuan'
                )
                ->where('karyawans.dept', auth()->user()->subrole)
                ->whereNotNull('pengajuan_id.exp_id')
                ->whereBetween('pengajuan_id.updated_at', [$date1, $date2])
                ->get();
        } else {
            $data = DB::table('pengajuan_id')
                ->join('karyawans', 'pengajuan_id.nrp', '=', 'karyawans.nrp')
                ->select(
                    'karyawans.nama',
                    'karyawans.nik',
                    'karyawans.nrp',
                    'karyawans.jabatan',
                    'karyawans.dept',
                    'karyawans.perusahaan',
                    'karyawans.doh',
                    'pengajuan_id.upload_foto',
                    'pengajuan_id.upload_ktp',
                    'pengajuan_id.upload_skd',
                    'pengajuan_id.tgl_pengajuan',
                    'pengajuan_id.exp_id',
                    'pengajuan_id.jenis_pengajuan_id',
                    'pengajuan_id.status_pengajuan'
                )
                ->whereNotNull('pengajuan_id.exp_id')
                ->whereBetween('pengajuan_id.updated_at', [$date1, $date2])
                ->get();
        }


        // $pdf = Pdf::loadView('cetak.report-tarikanId', ['data' => $data, 'date1' => $date1, 'date2' => $date2])
        //     ->setPaper('A4', 'landscape');
        // return response()->streamDownload(function () use ($pdf) {
        //     echo $pdf->stream();
        // }, 'report-id' . $date1 . 'sampai' . $date2 . '.pdf');
        $pdf = Pdf::loadView('cetak.report-tarikanId', [
            'data' => $data,
            'date1' => $date1,
            'date2' => $date2
        ])->setPaper('A4', 'landscape');

        return $pdf->stream('report-id' . $date1 . 'sampai' . $date2 . '.pdf');
    }

    public function reportKimper($date1, $date2)
    {
        if (auth()->user()->role === 'pimpinan') {
            $data = DB::table('pengajuan_kimper')
                ->join('karyawans', 'pengajuan_nrp.nrp', '=', 'karyawans.nrp')
                ->select(
                    'karyawans.nama',
                    'karyawans.nik',
                    'karyawans.nrp',
                    'karyawans.jabatan',
                    'karyawans.dept',
                    'karyawans.perusahaan',
                    'karyawans.doh',
                    'pengajuan_kimper.upload_foto',
                )
                ->where('karyawans.dept', auth()->user()->subrole)
                ->whereNotNull('pengajuan_id.exp_id')
                ->whereBetween('pengajuan_id.updated_at', [$date1, $date2])
                ->get();
        } else {
            $data = DB::table('pengajuan_kimper')
                ->join('karyawans', 'pengajuan_nrp.nrp', '=', 'karyawans.nrp')
                ->select(
                    'karyawans.nama',
                    'karyawans.nik',
                    'karyawans.nrp',
                    'karyawans.jabatan',
                    'karyawans.dept',
                    'karyawans.perusahaan',
                    'karyawans.doh',
                    'pengajuan_kimper.upload_foto',
                )
                ->whereNotNull('pengajuan_id.exp_id')
                ->whereBetween('pengajuan_id.updated_at', [$date1, $date2])
                ->get();
        }

        // $pdf = Pdf::loadView('cetak.report-tarikanId', ['data' => $data, 'date1' => $date1, 'date2' => $date2])
        //     ->setPaper('A4', 'landscape');
        // return response()->streamDownload(function () use ($pdf) {
        //     echo $pdf->stream();
        // }, 'report-id' . $date1 . 'sampai' . $date2 . '.pdf');
        $pdf = Pdf::loadView('cetak.report-tarikanId', [
            'data' => $data,
            'date1' => $date1,
            'date2' => $date2
        ])->setPaper('A4', 'landscape');

        return $pdf->stream('report-id' . $date1 . 'sampai' . $date2 . '.pdf');
    }

    public function render()
    {
        return view('livewire.report');
    }
}
