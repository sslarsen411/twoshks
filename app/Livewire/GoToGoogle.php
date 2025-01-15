<?php

namespace App\Livewire;

use App\Models\Review;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use App\Mail\NewReviewThankYou;
use Illuminate\Support\Facades\Mail;

class GoToGoogle extends Component{
    public string $url;
    public string $answer;
    public function mount(){        
        $this->url = 'https://search.google.com/local/writereview?placeid='. session('location.PID'); 
    }
    public function udDB(){
        $rev = Review::find(session('reviewID'));
        $rev->update([ 'status' => 'Posted']);
       
    
    // ToDo: Send notification email to client
    
        Mail::to(session('cust.email',  session('cust.first_name') . ' ' . session('cust.last_name')))->send(new NewReviewThankYou([
            'first_name' => session('cust.first_name'),
            'review' => $rev->review,
            'company' => session('location.company')
        ]));
        LOG::info('Review: ' . $rev->id .' thank you email sent to: ' . session('cust.email'));
        return redirect()->away($this->url); 
    }
    public function render()
    {
        return view('livewire.go-to-google');
    }
}