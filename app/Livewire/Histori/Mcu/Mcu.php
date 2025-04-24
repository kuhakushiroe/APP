<?php

namespace App\Livewire\Histori\Mcu;

use App\Models\Mcu as ModelsMcu;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Mcu extends Component
{

    use WithPagination, WithoutUrlPagination;
    public $search = '';
    #[Title('Histori MCU')]
    public function render()
    {
        $historimcus = ModelsMcu::select('mcu.*', 'karyawans.*', 'mcu.status as mcuStatus', 'mcu.id as id_mcu', 'mcu.exp_mcu as ExpMcu')
            ->join('karyawans', 'karyawans.nrp', '=', 'mcu.id_karyawan')
            ->whereAny(['karyawans.nrp', 'karyawans.nama', 'karyawans.jabatan', 'karyawans.dept'], 'like', '%' . $this->search . '%')
            ->where('mcu.status_', '=', "close")
            ->whereNotNull('mcu.exp_mcu')
            ->orderBy('mcu.tgl_mcu', 'desc')
            ->paginate(10);
        return view('livewire.histori.mcu.mcu', compact('historimcus'));
    }
}
