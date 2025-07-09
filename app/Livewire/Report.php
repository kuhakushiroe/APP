<?php

namespace App\Livewire;

use Livewire\Attributes\Title;
use Livewire\Component;

class Report extends Component
{
    #[Title('Report')]
    public $date_mcu1, $date_mcu2;
    public $date_id1, $date_id2;
    public $date_kimper1, $date_kimper2;
    public function mount()
    {
        $this->date_mcu1 = date('Y-m-d');
        $this->date_mcu2 = date('Y-m-d');
        $this->date_id1 = date('Y-m-d');
        $this->date_id2 = date('Y-m-d');
        $this->date_kimper1 = date('Y-m-d');
        $this->date_kimper2 = date('Y-m-d');
    }
    public function render()
    {
        return view('livewire.report');
    }
}
