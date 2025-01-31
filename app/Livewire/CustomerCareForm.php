<?php

namespace App\Livewire;

use AllowDynamicProperties;
use App\Mail\CustomerCare;
use App\Mail\CustomerServiceNeeded;
use App\Models\Customer;
use App\Models\Review;
use App\Traits\SiteHelpers;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Livewire\Component;

#[AllowDynamicProperties]
class CustomerCareForm extends Component {
    use SiteHelpers;

    //use LivewireAlert;
    public string $concerns = '';
    public bool $ckCallMe = false;
    public string $phone = '';
    public Review $review;
    public Customer $customer;
    protected $rules = [
        'concerns' => 'required|min:6',
        'phone' => 'phone:US|min:9',
    ];
    protected $messages = [
        'concerns.required' => 'Please type something',
        'phone.phone' => 'Enter a valid phone number',
    ];
    private string $url = '/home';

    public function mount(): void
    {
        $this->customer = Customer::find(session('cust.id'));

    }

    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    public function submitForm(): void
    {
        $this->handlePhoneCallRequirement();
        $this->updateReviewWithConcerns();
        $this->sendCustomerEmails();
        // $this->notifyUser();
        alert()
            ->html(
                'We hear you',
                '<h3 class="text-xl text-balance mb-5">'.
                session('location.company').
                ' has been notified about your concerns</h3><p class="text-balance">They will contact you shortly. A confirmation email has been sent to '.
                session('cust.email').
                '.</p>',
                'info'
            )
            ->showConfirmButton('OK', '#3085d6');
        $this->doRedirect($this->url);
    }

    private function handlePhoneCallRequirement(): void
    {
        if ($this->ckCallMe) {
            if (empty($this->phone)) {
                $this->dispatch(
                    'phError',
                    title: 'No phone number',
                    text: 'Enter your phone number or uncheck the box'
                );
            } else {
                $this->phone = $this->toE164($this->phone);
                $this->customer->update(['phone' => $this->phone]);
            }


        }
    }

    private function updateReviewWithConcerns(): void
    {
        $this->review = Review::find(session('reviewID'));
        $this->review->update([
            'review' => strip_tags($this->concerns),
            'status' => Review::NEGATIVE,
        ]);
    }

    private function sendCustomerEmails(): void
    {
        $customerName = session('cust.first_name', '<>').' '.
            session('cust.last_name', '<>');

        Mail::to(
            session('cust.email'), $customerName)->send(new CustomerCare([
            'first_name' => $customerName,
            'review' => $this->review->review,
            'company' => session('location.company'),
        ]));

        $phoneMessage = $this->phone
            ? 'They have requested someone call them at '.$this->fromE164($this->phone)
            : 'no phone number given';

        Mail::to(
            session('location.support_email'),
            session('location.company')
        )
            ->cc(session('location.email'))
            ->send(new CustomerServiceNeeded([
                'first_name' => session('cust.first_name', '<>'),
                'last_name' => session('cust.last_name', '<>'),
                'email' => session('cust.email', '<>'),
                'phone' => $phoneMessage,
                'min_rate' => session('location.min_rate', '<>'),
                'rating' => session('rating')[0],
                'review' => $this->review->review,
            ]));
    }

    public function render(): View
    {
        return view('livewire.customer-care-form');
    }

    private function notifyUser(): void
    {
        alert()
            ->html(
                'We hear you',
                '<h3 class="text-xl text-balance mb-5">'.
                session('location.company').
                ' has been notified about your concerns</h3><p class="text-balance">They will contact you shortly. A confirmation email has been sent to '.
                session('cust.email').
                '.</p>',
                'info'
            )
            ->showConfirmButton('OK', '#3085d6');
    }
}
