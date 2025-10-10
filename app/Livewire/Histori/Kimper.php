<?php

namespace App\Livewire\Histori;

use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;

class Kimper extends Component
{
    public $search;
    #[Title('Histori-Kimper')]
    public function render()
    {
        if (auth()->user()->role === 'superadmin') {
            $pengajuankimper = DB::table('pengajuan_kimper')
                ->select('pengajuan_kimper.*', 'karyawans.*', 'pengajuan_kimper.id as id_pengajuan', 'pengajuan_kimper.updated_at as tglaccept')
                ->join('karyawans', 'karyawans.nrp', '=', 'pengajuan_kimper.nrp')
                ->where('pengajuan_kimper.status_pengajuan', '=', '2')
                ->whereAny(['karyawans.nik', 'karyawans.nrp', 'karyawans.nama'], 'LIKE', '%' . $this->search . '%')
                ->paginate(10);
        } else if (auth()->user()->role === 'karyawan') {
            $pengajuankimper = DB::table('pengajuan_kimper')
                ->select('pengajuan_kimper.*', 'karyawans.*', 'pengajuan_kimper.id as id_pengajuan', 'pengajuan_kimper.updated_at as tglaccept')
                ->join('karyawans', 'karyawans.nrp', '=', 'pengajuan_kimper.nrp')
                ->where('pengajuan_kimper.nrp', auth()->user()->username)
                ->where('pengajuan_kimper.status_pengajuan', '=', '2')
                ->whereAny(['karyawans.nik', 'karyawans.nrp', 'karyawans.nama'], 'LIKE', '%' . $this->search . '%')
                ->paginate(10);
        } else {
            $pengajuankimper = DB::table('pengajuan_kimper')
                ->select('pengajuan_kimper.*', 'karyawans.*', 'pengajuan_kimper.id as id_pengajuan', 'pengajuan_kimper.updated_at as tglaccept')
                ->join('karyawans', 'karyawans.nrp', '=', 'pengajuan_kimper.nrp')
                ->where('karyawans.dept', auth()->user()->subrole)
                ->where('pengajuan_kimper.status_pengajuan', '=', '2')
                ->whereAny(['karyawans.nik', 'karyawans.nrp', 'karyawans.nama'], 'LIKE', '%' . $this->search . '%')
                ->paginate(10);
        }
        return view('livewire.histori.kimper', compact('pengajuankimper'));
    }
}
