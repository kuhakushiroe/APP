<?php

namespace App\Livewire\Profil;

use Livewire\Attributes\Title;
use Livewire\Component;

class Password extends Component
{

    #[Title('Password')]
    public $search = '';
    public $form = false;
    public $username, $name, $email, $password, $role, $subrole, $id_user, $wa;
    public function render()
    {

        return view('livewire.profil.password');
    }
}
