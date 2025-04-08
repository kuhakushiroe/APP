<?php

namespace App\Livewire\Histori;

use Livewire\Attributes\Title;
use Livewire\Component;

class Id extends Component
{
    #[Title('Histori-ID')]
    public function render()
    {
        return view('livewire.histori.id');
    }
}
