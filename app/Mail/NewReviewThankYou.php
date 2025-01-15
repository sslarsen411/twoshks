<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewReviewThankYou extends Mailable
{
    use Queueable, SerializesModels;
   
    public function __construct(public $data){
        //
    }   
    public function envelope(){
        return new Envelope(
            subject: $this->data['first_name'] . ', here\'s the review you wrote for ' . $this->data['company'],
        );
    }
    public function content(): Content    {
        return new Content(
            view: 'email.review-thank-you',
            with: [
                'first_name' => $this->data['first_name'],
                'review' => $this->data['review'],
                'company' => $this->data['company'],
            ]           
        );
    }
    public function attachments(): array
    {
        return [];
    }
}
