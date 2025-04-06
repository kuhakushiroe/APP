<?php

namespace App\Livewire\Page;

use Livewire\Attributes\Title;
use Livewire\Component;

class Notfound extends Component
{
    #[Title('Under Construction')]
    public $page;
    public function render()
    {
        return view('livewire.page.notfound', [
            'page' => $this->page,
        ]);
    }
}
