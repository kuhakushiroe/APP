<?php

namespace App\Http\Controllers\cetak;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CetakRegister extends Controller
{
    //
    public function registerID($date1, $date2)
    {
        $data = DB::table('pengajuan_id')
            ->join('karyawans', 'pengajuan_id.nrp', '=', 'karyawans.nrp')
            ->select(
                'pengajuan_id.*',
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
            ->whereBetween('pengajuan_id.updated_at', [$date1 . ' 00:00:00', $date2 . ' 23:59:59'])
            ->get();
        $pdf = Pdf::loadView('cetak.register-id', [
            'data' => $data,
            'date1' => $date1,
            'date2' => $date2
        ])->setPaper('A4', 'landscape');

        return $pdf->stream('register-id' . $date1 . 'sampai' . $date2 . '.pdf');
    }

    public function registerKIMPER($date1, $date2)
    {
        $carinrptanggal = DB::table('pengajuan_kimper')
            ->whereNotNull('exp_kimper')
            ->whereBetween('updated_at', [$date1 . ' 00:00:00', $date2 . ' 23:59:59'])
            ->distinct()
            ->pluck('nrp');
        //dd($carinrptanggal);
        $data = DB::table('pengajuan_kimper_versatility')
            ->join('versatility', 'versatility.id', '=', 'pengajuan_kimper_versatility.id_versatility')
            ->join('pengajuan_kimper', 'pengajuan_kimper.id', '=', 'pengajuan_kimper_versatility.id_pengajuan_kimper')
            ->join('karyawans', 'pengajuan_kimper.nrp', '=', 'karyawans.nrp')
            ->select(
                'karyawans.*',
                'pengajuan_kimper.*',
                'pengajuan_kimper_versatility.*',
                'versatility.*',
                'versatility.versatility as nama_versatility'
            )
            ->whereIn('pengajuan_kimper.nrp', $carinrptanggal) // pakai hasil pluck
            ->get();
        $pengajuan_kimper = DB::table('pengajuan_kimper as pk')
            ->join(DB::raw('(SELECT nrp, MAX(updated_at) as max_updated FROM pengajuan_kimper GROUP BY nrp) as latest'), function ($join) {
                $join->on('pk.nrp', '=', 'latest.nrp')
                    ->on('pk.updated_at', '=', 'latest.max_updated');
            })
            ->join('karyawans as k', 'pk.nrp', '=', 'k.nrp')
            ->whereIn('pk.nrp', $carinrptanggal)
            ->select('pk.*', 'k.nama', 'k.nrp') // sesuaikan kolom dari tabel karyawans
            ->get();
        $pdf = Pdf::loadView('cetak.register-kimper', [
            'nrp' => $carinrptanggal,
            'data' => $data,
            'pengajuan_kimper' => $pengajuan_kimper,
            'date1' => $date1,
            'date2' => $date2
        ])->setPaper('A4', 'landscape');

        return $pdf->stream('register-id' . $date1 . 'sampai' . $date2 . '.pdf');
    }
    public function formulirKImper($id)
    {
        $data = DB::table('pengajuan_kimper')
            ->join('karyawans', 'pengajuan_kimper.nrp', '=', 'karyawans.nrp')
            ->select(
                'karyawans.*',
                'pengajuan_kimper.*'
            )
            ->where('pengajuan_kimper.id', $id)->first();
        $mcu = DB::table('mcu')->where('id_karyawan', $data->nrp)->first();
        $pdf = Pdf::loadView('cetak.formulir-kimper', [
            'data' => $data,
            'mcu' => $mcu,
        ])->setPaper('A4', 'landscape');
        return $pdf->stream('formulir-kimper TEST.pdf');
        //return $pdf->stream('formulir-kimper ' . $data->nrp ?? 'nrp' . '-' . $data->nama ?? 'nama' . '-' . $data->tgl_pengajuan ?? 'tgl_pengajuan' . '.pdf');
    }
}
