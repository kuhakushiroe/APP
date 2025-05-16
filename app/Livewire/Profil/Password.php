<?php

namespace App\Livewire\Profil;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Hash;

class Password extends Component
{
    #[Title('Password')]
    public $search = '';
    public $form = false;
    public $id_user;
    public $password;

    public function UpdatePassword()
    {
        $user = User::find($this->id_user);
        $user->update([
            'password' => Hash::make($this->password)
        ]);

        $this->dispatch(
            'alert',
            type: 'success',
            title: 'Berhasil',
            text: 'Anda Berhasil Edit Password',
            position: 'center',
            confirm: true,
            redirect: '/logout',
        );
    }


    public function render()
    {
        $user = User::where('username', auth()->user()->username)->first();
        $this->id_user = $user->id;
        return view('livewire.profil.password', [
            'user' => $user, //buat bahan nanti jika ada ubah nomor hp nama dll
        ]);
    }
}
