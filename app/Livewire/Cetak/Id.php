<?php

namespace App\Livewire\Cetak;

use App\Models\Karyawan;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\Features\SupportPagination\WithoutUrlPagination;
use Livewire\WithPagination;

class Id extends Component
{
    use WithPagination, WithoutUrlPagination, WithFileUploads;
    public $search = '';
    #[Title('Cetak ID')]
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
        $departments = DB::table('departments')
            ->pluck('description_department', 'name_department')
            ->toArray();
        return view('livewire.cetak.id', compact('karyawans', 'departments'));
    }
}
