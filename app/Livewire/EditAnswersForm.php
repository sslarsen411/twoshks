<?php

namespace App\Livewire;

use App\Models\Review;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class EditAnswersForm extends Component
{
    public $answers = [];
    public $review;

    public function mount()
    {
        $this->review = Review::find(session('reviewID'));
        $this->answers = unserialize($this->review->answers);
        Cache::put('answers', $this->answers, now()->addMinutes(60));
    }

    public function submitForm()
    {
        $this->review->update(['answers' => serialize(array_map('strip_tags', $this->answers))]);
        $this->dispatch('ansUpdated', title: 'Your answer was updated');
    }

    public function render()
    {
        return view('livewire.edit-answers-form');
    }
}
