<?php

namespace App\Livewire;

use App\Mail\CustomerCare;
use App\Mail\CustomerServiceNeeded;
use App\Models\Review;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Livewire\Component;

//use Jantinnerezo\LivewireAlert\LivewireAlert;


class CustomerCareForm extends Component {
    //use LivewireAlert;
    public $concerns = '';
    public $ckCallMe = false;
    public $phone;
    public $review;
    protected $rules = [
        'concerns' => 'required|min:6',
        'phone' => 'phone:US|min:9',
    ];

    protected $messages = [
        'concerns.required' => 'Please type something',
        'phone.phone' => 'Enter a valid phone number',
    ];

//    public function mount()
//    {
//        ray(session()->all());
//    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function submitForm(): mixed
    {
        //    $this->validate();
        if ($this->ckCallMe) {
            if (empty($this->phone)) {
                $this->dispatch('phError', title: 'No phone number',
                    text: 'Enter your phone number or uncheck the box');
                return view('livewire.customer-care-form');
            }
        }

        $this->review = Review::find(session('reviewID'));
        ray($this->review);
        $this->review->update(['review' => strip_tags($this->concerns), 'status' => 'Negative']);
        Mail::to(session('cust.email'),
            session('cust.first_name').' '.session('cust.last_name'))->send(new CustomerCare([
            'first_name' => session('cust.first_name', '<>'),
            'review' => $this->review->review,
            'company' => session('location.company')
        ]));
        
        Mail::to(session('location.support_email'),
            session('location.company'))->cc(session('location.email'))->send(new CustomerServiceNeeded([
            'first_name' => session('cust.first_name', '<>'),
            'last_name' => session('cust.last_name', '<>'),
            'email' => session('cust.email', '<>'),
            'phone' => $this->phone ? 'They have requested someone call them at '.$this->phone : 'no phone number given',
            'min_rate' => session('location.min_rate', '<>'),
            'rating' => session('rating')[0],
            'review' => $this->review->review,
        ]));

        alert()->html('We hear you', '<h3 class="text-xl text-balance mb-5"> '.session('location.company').' has been notified about your concerns</h3>
                 <p class="text-balance">They will contact you shortly. A confirmation email has been sent to '.session('cust.email').'.</p>',
            'info')
            ->showConfirmButton('OK', '#3085d6');
        return redirect('/home');
    }

    public function render(): View
    {
        return view('livewire.customer-care-form');
    }
}
