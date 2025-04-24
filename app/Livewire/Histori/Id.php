<?php

namespace App\Livewire\Histori;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;

class Id extends Component
{
    #[Title('Histori-ID')]
    public function render()
    {
        if (auth()->user()->role === 'superadmin') {
            $pengajuanid = DB::table('pengajuan_id')
                ->select('pengajuan_id.*', 'karyawans.*', 'pengajuan_id.id as id_pengajuan')
                ->join('karyawans', 'karyawans.nrp', '=', 'pengajuan_id.nrp')
                ->where('pengajuan_id.status_pengajuan', '=', '2')
                ->paginate(10);
        } else if (auth()->user()->role === 'karyawan') {
            $pengajuanid = DB::table('pengajuan_id')
                ->select('pengajuan_id.*', 'karyawans.*', 'pengajuan_id.id as id_pengajuan')
                ->join('karyawans', 'karyawans.nrp', '=', 'pengajuan_id.nrp')
                ->where('pengajuan_id.nrp', auth()->user()->username)
                ->where('pengajuan_id.status_pengajuan', '=', '2')
                ->paginate(10);
        } else {
            $pengajuanid = DB::table('pengajuan_id')
                ->select('pengajuan_id.*', 'karyawans.*', 'pengajuan_id.id as id_pengajuan')
                ->join('karyawans', 'karyawans.nrp', '=', 'pengajuan_id.nrp')
                ->where('karyawans.dept', auth()->user()->subrole)
                ->where('pengajuan_id.status_pengajuan', '=', '2')
                ->paginate(10);
        }
        return view('livewire.histori.id', compact('pengajuanid'));
    }
}
