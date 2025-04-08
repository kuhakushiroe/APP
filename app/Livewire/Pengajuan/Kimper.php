<?php

namespace App\Livewire\Pengajuan;

use Livewire\Attributes\Title;
use Livewire\Component;

class Kimper extends Component
{
    #[Title('Kimper')]
    public function render()
    {
        return view('livewire.pengajuan.kimper');
    }
}
