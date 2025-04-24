<?php

namespace App\Livewire;

use App\Models\Karyawan;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\Features\SupportPagination\WithoutUrlPagination;
use Livewire\WithPagination;

class CetakKartuID extends Component
{
    use WithPagination, WithoutUrlPagination, WithFileUploads;
    public $search = '';
    #[Title('Cetak ID')]
    public function render()
    {
        if (auth()->user()->hasRole('superadmin') || auth()->user()->subrole == 'SHE') {
            $karyawans = Karyawan::whereAny(['nik', 'nrp', 'nama', 'status', 'dept'], 'LIKE', '%' . $this->search . '%')
                ->orderByRaw("status = 'non aktif' ASC")
                ->paginate(10)
                ->withQueryString();
        } else {
            $karyawans = Karyawan::whereAny(['nik', 'nrp', 'nama', 'status'], 'LIKE', '%' . $this->search . '%')
                ->orderByRaw("status = 'non aktif' ASC")
                ->where('dept', auth()->user()->subrole)
                ->paginate(10)
                ->withQueryString();
        }
        return view('livewire.cetak-kartu-i-d', compact('karyawans'));
    }
}
