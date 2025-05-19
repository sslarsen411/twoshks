<?php

namespace App\Livewire;

use App\Mail\NewReviewThankYou;
use App\Models\Review;
use App\Traits\SiteHelpers;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class GoToGoogle extends Component {
    use SiteHelpers;

    public string $url;
    public string $answer;
    public string $review;
    public string $reply;

    public function mount(): void
    {
        $this->url = 'https://search.google.com/local/writereview?placeid='.session('location.PID');
    }

    public function updateAndNotifyReview(): void
    {
        // Fetch needed session variables
        //  $reviewId = session('reviewID');
        $customerEmail = session('cust.email');
//        $customerName = session('cust.first_name').' '.session('cust.last_name');
        $companyName = session('location.company');
        $companyEmail = session('location.support_email');
        $customerFirstName = session('cust.first_name');

        // Retrieve the review and update its status
        $reviewCollection = Review::find(session('reviewID'));
        $reviewCollection->update(['status' => Review::POSTED]);

        // Send a notification email to the client
        $this->sendNotificationEmail($customerEmail, $customerFirstName, $this->review, $this->reply, $companyName,
            $companyEmail);

        // Log the email notification
        Log::info('Review notification sent', [
            'review_id' => session('reviewID'),
            'email' => $customerEmail,
            'status' => 'sent'
        ]);
        $this->doRedirect($this->url, true);
    }

    /**
     * Send a notification email to the customer.
     *
     * @param  string  $email
     * @param  string  $customerFirstName
     * @param  string  $review
     * @param  string  $reply
     * @param  string  $company
     * @param  string  $replyTo
     * @return void
     * @oaram string $reply,
     */
    protected function sendNotificationEmail(
        string $email,
        string $customerFirstName,
        string $review,
        string $reply,
        string $company,
        string $replyTo
    ): void {
        // $review = unserialize($inReview);

        //    ray($review->review);
        //    ray($review->reply);

        Mail::to($email)->send(new NewReviewThankYou([
            'first_name' => $customerFirstName,
            'review' => $review,
            'reply' => $reply,
            'company' => $company,
            'replyTo' => $replyTo
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

    public function render(): object
    {
        return view('livewire.go-to-google');
    }
}
