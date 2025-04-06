<?php

namespace App\Livewire\Departments;

use App\Models\Departments as ModelsDepartments;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Departments extends Component
{
    use WithPagination, WithoutUrlPagination;
    public $search = '';
    public $form = false;
    public $id_department, $name_department, $description_department, $data;
    protected $listeners = ['delete'];
    #[Title('Departments')]
    public function open()
    {
        $this->form = true;
    }
    public function close()
    {
        $this->form = false;
        $this->id_department = '';
        $this->name_department = '';
        $this->description_department = '';
    }
    public function edit($id)
    {
        $departments = ModelsDepartments::find($id);
        $this->id_department = $departments->id;
        $this->name_department = $departments->name_department;
        $this->description_department = $departments->description_department;
        $this->open();
    }
    public function store()
    {
        $this->validate([
            'name_department' => 'required|min:2',
            'description_department' => 'required',
        ], [
            'name_department.required' => 'Name Department Harus Diisi',
            'name_department.min' => 'Name Department Minimal 8 Karakter',
            'description_department.required' => 'Description Department Harus Diisi',
            'description_department.min' => 'Description Department Minimal 16 Karakter',
        ]);

        ModelsDepartments::updateOrCreate([
            'id' => $this->id_department,
        ], [
            'name_department' => $this->name_department,
            'description_department' => $this->description_department,
        ]);
        $this->close();
        if (!empty($this->id_department)) {
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
            redirect: '/departments',
        );
        return;
    }
    public function delete(int $id)
    {
        ModelsDepartments::find($id)->delete();
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
        ModelsDepartments::withTrashed()->restore();
    }
    public function restore(int $id)
    {
        // Mencari data yang sudah di-soft delete dengan menggunakan withTrashed()
        $department = ModelsDepartments::withTrashed()->find($id);

        if ($department) {
            // Mengembalikan data yang telah di-soft delete
            $department->restore();
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
        $departments = ModelsDepartments::where('name_department', 'like', '%' . $this->search . '%')
            ->withTrashed()
            ->paginate(5);
        return view('livewire.departments.departments', [
            'departments' => $departments,
        ]);
    }
}
