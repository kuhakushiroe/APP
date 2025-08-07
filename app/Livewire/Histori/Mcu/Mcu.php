<?php

namespace App\Livewire\Histori\Mcu;

use App\Models\Karyawan;
use App\Models\Mcu as ModelsMcu;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Mcu extends Component
{

    use WithPagination, WithoutUrlPagination;
    public $search = '';
    public $editform = false;
    public $tgl_mcu, $exp_mcu, $tgl_verifikasi, $nrp, $id_mcu;

    #[Title('Histori MCU')]
    public function close()
    {
        $this->editform = false;
    }
    public function edit($id)
    {
        $this->editform = true;
        $carimcu = ModelsMcu::find($id);
        $this->id_mcu = $carimcu->id;
        $this->nrp = $carimcu->id_karyawan;
        $this->tgl_mcu = $carimcu->tgl_mcu;
        $this->exp_mcu = $carimcu->exp_mcu;
        $this->tgl_verifikasi = $carimcu->tgl_verifikasi;
    }
    public function store()
    {
        ModelsMcu::where('id', $this->id_mcu)->update([
            'tgl_mcu' => $this->tgl_mcu,
            'exp_mcu' => $this->exp_mcu,
            'tgl_verifikasi' => $this->tgl_verifikasi
        ]);
        Karyawan::where('nrp', $this->nrp)->update([
            'exp_mcu' => $this->exp_mcu
        ]);
        $this->dispatch(
            'alert',
            type: 'success',
            title: 'Berhasil',
            text: 'Berhasil Ubah Tanggal',
            position: 'center',
            confirm: true,
            redirect: '/histori-mcu',
        );
        $this->reset();
        return;
    }
    public function render()
    {
        if (in_array(auth()->user()->role, ['superadmin', 'she', 'dokter'])) {
            $historimcus = ModelsMcu::select('mcu.*', 'karyawans.*', 'mcu.status as mcuStatus', 'mcu.id as id_mcu', 'mcu.exp_mcu as ExpMcu')
                ->join('karyawans', 'karyawans.nrp', '=', 'mcu.id_karyawan')
                ->whereAny(['karyawans.nrp', 'karyawans.nama', 'karyawans.jabatan', 'karyawans.dept'], 'like', '%' . $this->search . '%')
                ->where('mcu.status_', '=', "close")
                ->whereNotNull('mcu.exp_mcu')
                ->orderBy('mcu.tgl_mcu', 'desc')
                ->paginate(10);
        } else {
            $historimcus = ModelsMcu::select('mcu.*', 'karyawans.*', 'mcu.status as mcuStatus', 'mcu.id as id_mcu', 'mcu.exp_mcu as ExpMcu')
                ->join('karyawans', 'karyawans.nrp', '=', 'mcu.id_karyawan')
                ->whereAny(['karyawans.nrp', 'karyawans.nama', 'karyawans.jabatan', 'karyawans.dept'], 'like', '%' . $this->search . '%')
                ->where('karyawans.dept', auth()->user()->subrole)
                ->where('mcu.status_', '=', "close")
                ->whereNotNull('mcu.exp_mcu')
                ->orderBy('mcu.tgl_mcu', 'desc')
                ->paginate(10);
        }
        return view('livewire.histori.mcu.mcu', compact('historimcus'));
    }
}
