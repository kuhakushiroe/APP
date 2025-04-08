<?php

namespace App\Livewire\Histori;

use Livewire\Attributes\Title;
use Livewire\Component;

class Kimper extends Component
{
    #[Title('Histori-Kimper')]
    public function render()
    {
        return view('livewire.histori.kimper');
    }
}
