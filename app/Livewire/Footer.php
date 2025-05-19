<?php
/* https://devdojo.com/tnylea/sharing-state-between-livewire-and-alpine */

namespace App\Livewire;

use Livewire\Component;

class Footer extends Component {

    public string $componentName = 'lgl-cookie';
    public bool $isOpen = false;

    public function openModal($inVal): void
    {
        $this->componentName = $inVal;
        $this->isOpen = true;
    }

    public function render(): object
    {
        return view('livewire.footer');
    }
}
