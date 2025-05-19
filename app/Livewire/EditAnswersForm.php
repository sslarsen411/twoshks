<?php

namespace App\Livewire;

use App\Models\Review;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class EditAnswersForm extends Component {
    public array $answers = [];
    public review $review;

    public function mount(): void
    {
        $this->review = Review::find(session('reviewID'));
        $this->answers = unserialize($this->review->answers);
        Cache::put('answers', $this->answers, now()->addMinutes(60));
    }

    public function submitForm(): void
    {
        $this->review->update(['answers' => serialize(array_map('strip_tags', $this->answers))]);
        // $this->dispatch('ansUpdated', title: 'Your answer was updated');
        $this->dispatch(
            'doAlert',
            title: 'Your answer was updated!',
            icon: "success"
        );
    }

    public function render(): object
    {
        return view('livewire.edit-answers-form');
    }
}
