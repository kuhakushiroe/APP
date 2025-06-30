<?php

namespace App\Http\Controllers;

use App\Models\Mcu;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class McuCetak extends Controller
{
    //
    public function skd($id)
    {
        $carimcu = Mcu::join('karyawans', 'karyawans.nrp', '=', 'mcu.id_karyawan')->where('mcu.id', $id)->first();
        $pdf = Pdf::loadView('cetak.skd', ['id' => $id,])
            ->set_option('dpi', '96')
            ->setPaper('a4', 'portrait');
        return $pdf->stream('mcu-' . $carimcu->nik . '-' . date('Y-m-d') . '.pdf');
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
        return $pdf->stream('mcu-' . $carimcu->nik . '-' . date('Y-m-d') . '.pdf');
    }
}
