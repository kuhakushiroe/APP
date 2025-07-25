<?php

namespace App\Http\Controllers;

use App\Models\Mcu;
use App\Models\ModelPengajuanID;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class McuCetak extends Controller
{
    //
    public function skd($id)
    {
        $carimcu = Mcu::join('karyawans', 'karyawans.nrp', '=', 'mcu.id_karyawan')->where('mcu.id', $id)->first();
        $pdf = Pdf::loadView('cetak.skd', ['id' => $id,])
            ->set_option('dpi', '96')
            ->setPaper('a4', 'portrait');
        return $pdf->stream('skd-' . $carimcu->nik . '-' . date('Y-m-d') . '.pdf');
    }
    public function cetak($id)
    {
        $carimcu = Mcu::join('karyawans', 'karyawans.nrp', '=', 'mcu.id_karyawan')->where('mcu.id', $id)->first();
        $pdf = Pdf::loadView('cetak.mcu', ['id' => $id,])
            ->set_option('dpi', '96')
            ->setPaper('a4', 'landscape');
        return $pdf->stream('mcu-' . $carimcu->nik . '-' . date('Y-m-d') . '.pdf');
    }
    public function cetakSub($id)
    {
        $carimcu = Mcu::join('karyawans', 'karyawans.nrp', '=', 'mcu.id_karyawan')->where('mcu.id', $id)->first();
        $pdf = Pdf::loadView('cetak.mcu-sub', ['id' => $id,])
            ->set_option('dpi', '96')
            ->setPaper('a4', 'landscape');
        return $pdf->stream('mcu-' . $carimcu->nik . '-' . date('Y-m-d') . '.pdf');
    }

    public function cetakLaik($id)
    {
        $carimcu = Mcu::join('karyawans', 'karyawans.nrp', '=', 'mcu.id_karyawan')->where('mcu.id', $id)->first();
        $pdf = Pdf::loadView('cetak.kartu-laik-kerja', ['id' => $id,])
            ->set_option('dpi', '96')
            ->setPaper('a4', 'landscape');
        return $pdf->stream('surat-laik-kerja-' . $carimcu->nik . '-' . date('Y-m-d') . '.pdf');
    }

    public function reportId($date1, $date2)
    {
        // $data = ModelPengajuanID::whereBetween('tgl_mcu', [$date1, $date2])->get();
        $data = DB::table('pengajuan_id')
            ->join('karyawans', 'pengajuan_id.nrp', '=', 'karyawans.nrp')
            ->select(
                'karyawans.nama',
                'karyawans.nik',
                'karyawans.nrp',
                'karyawans.jabatan',
                'karyawans.dept',
                'karyawans.perusahaan',
                'pengajuan_id.tgl_pengajuan',
                'pengajuan_id.exp_id',
                'pengajuan_id.jenis_pengajuan_id',
                'pengajuan_id.status_pengajuan'
            )
            ->whereNotNull('pengajuan_id.exp_id')
            ->whereBetween('pengajuan_id.updated_at', [$date1, $date2])
            ->get();

        $pdf = Pdf::loadView('cetak.report-tarikanId', ['data' => $data, 'date1' => $date1, 'date2' => $date2])
            ->setPaper('A4', 'landscape');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'report-id' . $date1 . 'sampai' . $date2 . '.pdf');
    }

    public function reportKimper($date1, $date2) {}
}
