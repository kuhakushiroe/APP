<?php

namespace App\Livewire\Profil;

use App\Models\User;
use Livewire\Attributes\Title;
use Livewire\Component;

class Password extends Component
{
    #[Title('Password')]
    public $search = '';
    public $form = false;
    public $id_user;

    public function UpdatePassword() {}

    public function render()
    {
        $user = User::where('username', auth()->user()->username)->first();
        $this->id_user = $user->id;
        return view('livewire.profil.password', [
            'user' => $user, //buat bahan nanti jika ada ubah nomor hp nama dll
        ]);
    }
}
