<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Queue\SerializesModels;

class NewReviewThankYou extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public $data)
    {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [
                new Address($this->data['replyTo'], $this->data['company']),
            ],
            subject: $this->data['first_name'] . ', thank you for your review',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'email.review-thank-you',
            with: [
                'first_name' => $this->data['first_name'],
                'review' => $this->data['review'],
                'reply' => $this->data['reply'],
                'company' => $this->data['company'],
            ]
        );
    }

    /**
     * @return Headers
     */
    public function headers(): Headers
    {
        return new Headers(
            messageId: 'Cust-Care@ravereview.guru',
            //  references: ['previous-message@example.com'],
            text: [
                'x-mailgun-native-send' => 'true',
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
