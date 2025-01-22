<?php

namespace App\Livewire;

use App\Models\Review;
use Illuminate\View\View;
use Livewire\Component;

class EditReview extends Component {
    public string $review;

    public function mount(): void
    {
        $this->dispatch('copyTextToClipboard', ['text' => $this->review]);
    }

    public function updated(): void
    {
        Review::find(session('reviewID'))->update(['review' => $this->review]);
        $this->dispatch('copyTextToClipboard', ['text' => $this->review]);
    }

    public function render(): View
    {
        return view('livewire.edit-review');
    }
}
