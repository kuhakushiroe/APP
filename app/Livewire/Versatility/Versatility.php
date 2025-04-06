<?php

namespace App\Livewire\Versatility;

use App\Models\Versatility as ModelsVersatility;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Versatility extends Component
{
    use WithPagination, WithoutUrlPagination;
    public $search = '';
    public $form = false;
    public $id_versatility, $versatility, $description;
    protected $listeners = ['delete'];

    #[Title('Versatility')]

    public function open()
    {
        $this->form = true;
    }
    public function close()
    {
        $this->form = false;
        $this->id_versatility = '';
        $this->versatility = '';
        $this->description = '';
    }
    public function edit($id)
    {
        $data = ModelsVersatility::find($id);
        $this->id_versatility = $data->id;
        $this->versatility = $data->versatility;
        $this->description = $data->description;
        $this->open();
    }
    public function store()
    {
        $this->validate([
            'versatility' => 'required|min:2',
            'description' => 'required',
        ], [
            'versatility.required' => 'Versatility Harus Diisi',
            'versatility.min' => 'Versatility Minimal 8 Karakter',
            'description.required' => 'Description Harus Diisi',
            'description.min' => 'Description Minimal 16 Karakter',
        ]);

        ModelsVersatility::updateOrCreate([
            'id' => $this->id_versatility,
        ], [
            'versatility' => $this->versatility,
            'description' => $this->description,
        ]);
        $this->close();
        if (!empty($this->id_versatility)) {
            $jenis = 'Edit';
        } else {
            $jenis = 'Tambah';
        }
        $this->dispatch(
            'alert',
            type: 'success',
            title: 'Berhasil',
            text: 'Berhasil ' . $jenis . ' Versatility',
            position: 'center',
            confirm: true,
            redirect: '/versatility',
        );
        return;
    }

    public function delete(int $id)
    {
        ModelsVersatility::find($id)->delete();
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
        ModelsVersatility::withTrashed()->restore();
    }
    public function restore(int $id)
    {
        // Mencari data yang sudah di-soft delete dengan menggunakan withTrashed()
        $data = ModelsVersatility::withTrashed()->find($id);

        if ($data) {
            // Mengembalikan data yang telah di-soft delete
            $data->restore();
        } else {
            // Menangani jika data tidak ditemukan
        }
    }
    public function render()
    {
        $versatility = ModelsVersatility::withTrashed()->paginate(10);
        return view(
            'livewire.versatility.versatility',
            ['versatilitys' => $versatility]
        );
    }
}
