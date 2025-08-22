<?php

namespace App\Livewire\Jabatan;

use App\Models\Jabatan as ModelsJabatan;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Features\SupportPagination\WithoutUrlPagination;
use Livewire\WithPagination;

class Jabatan extends Component
{
    use WithPagination, WithoutUrlPagination;
    public $search = '';
    public $form = false;
    public $id_jabatan, $nama_jabatan, $keterangan_jabatan, $data;
    protected $listeners = ['delete'];
    #[Title('Jabatan')]
    public function open()
    {
        $this->form = true;
    }
    public function close()
    {
        $this->form = false;
        $this->id_jabatan = '';
        $this->nama_jabatan = '';
        $this->keterangan_jabatan = '';
    }
    public function edit($id)
    {
        $jabatan = ModelsJabatan::find($id);
        $this->id_jabatan = $jabatan->id;
        $this->nama_jabatan = $jabatan->nama_jabatan;
        $this->keterangan_jabatan = $jabatan->keterangan_jabatan;
        $this->open();
    }
    public function store()
    {
        $this->validate([
            'nama_jabatan' => 'required|min:2',
            'keterangan_jabatan' => 'required',
        ], [
            'nama_jabatan.required' => 'Nama Jabatan Harus Diisi',
            'nama_jabatan.min' => 'Nama Jabatan Minimal 8 Karakter',
            'keterangan_jabatan.required' => 'Keterangan Jabatan Harus Diisi',
            'keterangan_jabatan.min' => 'Keterangan Jabatan Minimal 16 Karakter',
        ]);

        ModelsJabatan::updateOrCreate([
            'id' => $this->id_jabatan,
        ], [
            'nama_jabatan' => $this->nama_jabatan,
            'keterangan_jabatan' => $this->keterangan_jabatan,
        ]);
        $this->close();
        if (!empty($this->id_jabatan)) {
            $jenis = 'Edit';
        } else {
            $jenis = 'Tambah';
        }
        $this->dispatch(
            'alert',
            type: 'success',
            title: 'Berhasil',
            text: 'Berhasil ' . $jenis . ' Department',
            position: 'center',
            confirm: true,
            redirect: '/jabatan',
        );
        return;
    }
    public function delete(int $id)
    {
        ModelsJabatan::find($id)->delete();
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
        ModelsJabatan::withTrashed()->restore();
    }
    public function restore(int $id)
    {
        // Mencari data yang sudah di-soft delete dengan menggunakan withTrashed()
        $jabatan = ModelsJabatan::withTrashed()->find($id);

        if ($jabatan) {
            // Mengembalikan data yang telah di-soft delete
            $jabatan->restore();
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
        $jabatan = ModelsJabatan::withTrashed()->where('nama_jabatan', 'like', '%' . $this->search . '%')->paginate(10);
        return view('livewire.jabatan.jabatan', compact('jabatan'));
    }
}
