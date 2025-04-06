<?php

namespace App\Http\Controllers;

use App\Exports\KaryawansExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportKaryawans extends Controller
{
    //
    public function export()
    {
        return Excel::download(new KaryawansExport, 'karyawans-MIFA' . date('Y-m-d') . time() . '.xlsx');
    }
}
