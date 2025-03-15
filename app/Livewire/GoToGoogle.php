<?php

namespace App\Livewire;

use App\Mail\NewReviewThankYou;
use App\Models\Review;
use App\Traits\SiteHelpers;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Livewire\Component;

class GoToGoogle extends Component
{
    use SiteHelpers;

    public string $url;
    public string $answer;

    public function mount(): void
    {
        $this->url = 'https://search.google.com/local/writereview?placeid=' . session('location.PID');
    }

    public function updateAndNotifyReview(): void
    {
        // Fetch needed session variables
        $reviewId = session('reviewID');
        $customerEmail = session('cust.email');
//        $customerName = session('cust.first_name').' '.session('cust.last_name');
        $companyName = session('location.company');
        $customerFirstName = session('cust.first_name');

        // Retrieve the review and update its status
        $review = Review::find($reviewId);
        $review->update(['status' => Review::POSTED]);

        // Send notification email to client
        $this->sendNotificationEmail($customerEmail, $customerFirstName, $review, $companyName);

        // Log the email notification
        Log::info('Review notification sent', [
            'review_id' => $review->id,
            'email' => $customerEmail,
            'status' => 'sent'
        ]);
        $this->doRedirect($this->url, true);
    }


    /**
     * Send notification email to the customer.
     *
     * @param string $email
     * @param string $customerFirstName
     * @param Review $review
     * @param string $company
     * @return void
     */
    protected function sendNotificationEmail(
        string $email,
        string $customerFirstName,
        Review $review,
        string $company
    ): void
    {
        Mail::to($email)->send(new NewReviewThankYou([
            'first_name' => $customerFirstName,
            'review' => $review->review,
            'company' => $company,
        ]));
    }

//    public function udDB()
//    {
//        $rev = Review::find(session('reviewID'));
//        $rev->update(['status' => 'Posted']);
//
//        // ToDo: Send notification email to client
//
//        Mail::to(session('cust.email',
//            session('cust.first_name').' '.session('cust.last_name')))->send(new NewReviewThankYou([
//            'first_name' => session('cust.first_name'),
//            'review' => $rev->review,
//            'company' => session('location.company')
//        ]));
//        LOG::info('Review: '.$rev->id.' thank you email sent to: '.session('cust.email'));
//        return redirect()->away($this->url);
//    }

    public function render(): View
    {
        return view('livewire.go-to-google');
    }
}
