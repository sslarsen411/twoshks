<?php

namespace App\Livewire;

use Illuminate\View\View;
use Livewire\Component;

class Footer extends Component
{

    public $componentName = 'lgl-cookie';
    public $isOpen = false;

    public function openModal($inVal): void
    {
        $this->componentName = $inVal;
        $this->isOpen = true;
    }

    public function render(): view
    {
        return view('livewire.footer');
    }
}
