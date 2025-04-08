<?php

namespace App\Livewire\Pengajuan;

use Livewire\Attributes\Title;
use Livewire\Component;

class Id extends Component
{
    #[Title('ID')]
    public function render()
    {
        return view('livewire.pengajuan.id');
    }
}
