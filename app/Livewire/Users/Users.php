<?php

namespace App\Livewire\Users;

use App\Models\Departments;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Users extends Component
{
    use WithPagination, WithoutUrlPagination;
    protected $listeners = ['delete'];

    #[Title('Users')]
    public $search = '';
    public $form = false;
    public $edit_Password = false;
    public $username, $name, $email, $password, $role, $subrole, $id_user, $wa;
    public function open()
    {
        $this->form = true;
    }
    public function close()
    {
        $this->form = false;
    }
    public function store()
    {
        // Validasi input
        $this->validate([
            'name' => 'required',
            'username' => 'required|regex:/^[a-zA-Z0-9]+$/|min:4|max:20|unique:users,username,' . $this->id_user,  // Menambahkan pengecualian untuk edit
            'email' => 'required|email|unique:users,email,' . $this->id_user,  // Menambahkan pengecualian untuk edit
            'password' => $this->id_user ? 'nullable' : 'required',  // Password wajib diisi saat store
            'role' => 'required',
            'subrole' => 'required_if:role,admin',
            'wa' => 'required',
        ], [
            'name.required' => 'Name Harus Diisi',
            'username.required' => 'Username Harus Diisi',
            'username.unique' => 'Username sudah digunakan, silakan pilih yang lain',
            'username.regex' => 'Username hanya boleh mengandung huruf dan angka',
            'username.min' => 'Username minimal 4 karakter',
            'username.max' => 'Username maksimal 20 karakter',
            'email.required' => 'Email Harus Diisi',
            'email.email' => 'Email tidak valid',
            'email.unique' => 'Email sudah digunakan, silakan pilih yang lain',
            'password.required' => 'Password Harus Diisi',
            'role.required' => 'Role Harus Diisi',
            'subrole.required_if' => 'Subrole Harus Diisi jika Role adalah Admin',
            'wa.required' => 'No Handphone Harus Diisi dengan format nomor',
        ]);

        // Jika password tidak kosong, hash password tersebut
        $userData = [
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'role' => $this->role,
            'subrole' => $this->subrole,
            'wa' => $this->wa,
        ];

        // Jika password tidak kosong, hash password tersebut
        if (!empty($this->password)) {
            $userData['password'] = Hash::make($this->password);  // Hash password jika ada
        }

        // Simpan atau update user data, gunakan updateOrCreate jika ingin mengupdate berdasarkan id
        User::updateOrCreate([
            'id' => $this->id_user
        ], $userData);

        $this->close();

        // Reset input fields
        $this->reset(['name', 'username', 'email', 'password', 'role', 'subrole']);

        // Tentukan jenis aksi (Tambah/Edit)
        $jenis = !empty($this->id_user) ? 'Edit' : 'Tambah';

        // Kirim notifikasi sukses
        $this->dispatch(
            'alert',
            type: 'success',
            title: 'Berhasil',
            text: 'Berhasil ' . $jenis . ' User',
            position: 'center',
            confirm: true,
            redirect: '/users',
        );

        return;
    }

    public function delete(int $id)
    {
        $user = User::find($id);
        Karyawan::where('nrp', $user->username)->update(['status' => 'non aktif']);
        $user->delete();
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
        User::withTrashed()->restore();
    }


    public function restore(int $id)
    {
        // Mencari data yang sudah di-soft delete dengan menggunakan withTrashed()
        $user = User::withTrashed()->find($id);
        Karyawan::where('nrp', $user->username)->update(['status' => 'aktif']);
        if ($user) {
            // Mengembalikan data yang telah di-soft delete
            $user->restore();
        } else {
            // Menangani jika data tidak ditemukan
        }
    }


    public function mount()
    {
        $this->close();
    }
    public function edit($id)
    {
        $this->edit_Password = false;
        // Cek apakah user memiliki role 'superadmin', jika iya redirect ke halaman /notfound/users
        if (auth()->user()->hasRole('superadmin')) {
            $this->open();
        }

        // Ambil data pengguna berdasarkan ID
        $user = User::find($id);

        if (!$user) {
            // Jika user tidak ditemukan, redirect atau memberikan response yang sesuai
            return redirect()->to('/notfound/users');
        }

        // Set data user ke dalam properti yang sesuai
        $this->name = $user->name;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->subrole = $user->subrole;
        $this->id_user = $user->id; // Tidak perlu menampilkan atau menyertakan password saat edit
        $this->wa = $user->wa;

        // Set flag form menjadi true jika tidak ada redirect
        $this->form = true;
    }

    public function editPassword($id_user)
    {
        if (auth()->user()->hasRole('superadmin')) {
            $this->open();
        }
        $user = User::find($id_user);
        $this->edit_Password = true;
        $this->id_user = $id_user;
        $this->form = true;
    }

    public function updatePassword()
    {
        $user = User::find($this->id_user);
        $user->update([
            'password' => Hash::make($this->password)
        ]);

        $this->close();

        $this->dispatch(
            'alert',
            type: 'success',
            title: 'Berhasil',
            text: 'Berhasil Edit Password User',
            position: 'center',
            confirm: true,
            redirect: '/users',
        );
    }


    public function render()
    {
        $departments = Departments::all();
        if (auth()->user()->hasRole('admin')) {
            $users = User::whereAny(['name', 'username', 'email'], 'like', '%' . $this->search . '%')
                ->where('subrole', '=', auth()->user()->subrole)
                ->withTrashed()
                ->paginate(10);
        } else {
            $users = User::whereAny(['name', 'username', 'email'], 'like', '%' . $this->search . '%')->withTrashed()->paginate(10);
        }
        return view('livewire.users.users', [
            'users' => $users,
            'departments' => $departments
        ]);
    }
}
