<?php

namespace App\Livewire\Perusahaan;

use App\Models\Perusahaan as ModelsPerusahaan;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Features\SupportPagination\WithoutUrlPagination;
use Livewire\WithPagination;

class Perusahaan extends Component
{
    use WithPagination, WithoutUrlPagination;
    public $search = '';
    public $form = false;
    public $id_perusahaan, $nama_perusahaan, $keterangan_perusahaan, $data;
    protected $listeners = ['delete'];

    #[Title('Perusahaan')]
    public function open()
    {
        $this->form = true;
    }
    public function close()
    {
        $this->form = false;
        $this->id_perusahaan = '';
        $this->nama_perusahaan = '';
        $this->keterangan_perusahaan = '';
    }
    public function edit($id)
    {
        $perusahaan = ModelsPerusahaan::find($id);
        $this->id_perusahaan = $perusahaan->id;
        $this->nama_perusahaan = $perusahaan->nama_perusahaan;
        $this->keterangan_perusahaan = $perusahaan->keterangan_perusahaan;
        $this->open();
    }
    public function store()
    {
        $this->validate([
            'nama_perusahaan' => 'required|min:2',
            'keterangan_perusahaan' => 'required',
        ], [
            'nama_perusahaan.required' => 'Nama Perusahaan Harus Diisi',
            'nama_perusahaan.min' => 'Nama Perusahaan Minimal 8 Karakter',
            'keterangan_perusahaan.required' => 'Keterangan Perusahaan Harus Diisi',
            'keterangan_perusahaan.min' => 'Keterangan Perusahaan Minimal 16 Karakter',
        ]);

        ModelsPerusahaan::updateOrCreate([
            'id' => $this->id_perusahaan,
        ], [
            'nama_perusahaan' => $this->nama_perusahaan,
            'keterangan_perusahaan' => $this->keterangan_perusahaan,
        ]);
        $this->close();
        if (!empty($this->id_perusahaan)) {
            $jenis = 'Edit';
        } else {
            $jenis = 'Tambah';
        }
        $this->dispatch(
            'alert',
            type: 'success',
            title: 'Berhasil',
            text: 'Berhasil ' . $jenis . ' Perusahaan',
            position: 'center',
            confirm: true,
            redirect: '/perusahaan',
        );
        return;
    }
    public function delete(int $id)
    {
        ModelsPerusahaan::find($id)->delete();
    }
    public function deleteConfirm($id)
    {
        $this->dispatch(
            'confirm',
            id: $id
        );
    }
    public function restoreAll()
    {
        // Mengembalikan semua data yang di-soft delete
        ModelsPerusahaan::withTrashed()->restore();
    }
    public function restore(int $id)
    {
        // Mencari data yang sudah di-soft delete dengan menggunakan withTrashed()
        $perusahaan = ModelsPerusahaan::withTrashed()->find($id);

        if ($perusahaan) {
            // Mengembalikan data yang telah di-soft delete
            $perusahaan->restore();
        } else {
            // Menangani jika data tidak ditemukan
        }
    }
    public function mount()
    {
        $this->close();
    }
    public function render()
    {
        $perusahaan = ModelsPerusahaan::withTrashed()->where('nama_perusahaan', 'like', '%' . $this->search . '%')->paginate(10);
        return view('livewire.perusahaan.perusahaan', compact('perusahaan'));
    }
}
