<?php

namespace App\Livewire;

use App\Models\Review;
use Livewire\Component;

class EditReview extends Component
{
    public string $review;

    public function mount(){
        $this->dispatch ('copyTextToClipboard', ['text' => $this->review]); 
    }
    public function updated()    {
        Review::find(session('reviewID'))->update([ 'review' => $this->review]);
        $this->dispatch ('copyTextToClipboard', ['text' => $this->review]); 
    }
    public function render()
    {
        return view('livewire.edit-review');
    }
}
