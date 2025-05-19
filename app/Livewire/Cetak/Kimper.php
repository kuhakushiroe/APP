<?php

namespace App\Livewire\Cetak;

use Livewire\Component;
use App\Models\Karyawan;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\Features\SupportPagination\WithoutUrlPagination;

class Kimper extends Component
{
    use withPagination, withoutUrlPagination, WithFileUploads;
    public $search = '';
    #[Title('Cetak Kimper')]


    public function render()
    {
        if (in_array(auth()->user()->role, ['superadmin', 'she'])) {
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
        return view('livewire.cetak.kimper');
    }
}
