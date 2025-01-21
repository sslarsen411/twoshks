<?php

namespace App\Livewire;

use App\Mail\NewReviewThankYou;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Livewire\Component;

class GoToGoogle extends Component {
    public string $url;
    public string $answer;

    public function mount(): void
    {
        $this->url = 'https://search.google.com/local/writereview?placeid='.session('location.PID');
    }

    public function udDB(): RedirectResponse
    {
        $rev = Review::find(session('reviewID'));
        $rev->update(['status' => 'Posted']);


        // ToDo: Send notification email to client

        Mail::to(session('cust.email',
            session('cust.first_name').' '.session('cust.last_name')))->send(new NewReviewThankYou([
            'first_name' => session('cust.first_name'),
            'review' => $rev->review,
            'company' => session('location.company')
        ]));
        LOG::info('Review: '.$rev->id.' thank you email sent to: '.session('cust.email'));
        return redirect()->away($this->url);
    }

    public function render(): View
    {
        return view('livewire.go-to-google');
    }
}
