<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class CetakKartuController extends Controller
{
    //
    public function cetak($id)
    {
        $karyawan = Karyawan::where('nrp', $id)->first();
        $pdf = Pdf::loadView('cetak.kartu-desain', ['id' => $id, 'karyawans' => $karyawan])
            ->set_option('dpi', '200')
            ->setPaper(array(0, 0, 155, 258), 'portrait');
        return $pdf->stream('kartu-' . $id . '-' . date('Y-m-d') . '.pdf');
    }
}
