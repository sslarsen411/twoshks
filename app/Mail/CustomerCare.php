<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Queue\SerializesModels;

class CustomerCare extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public $data)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [
                new Address(session('location.support_email'), session('location.company')),
            ],
            subject: $this->data['first_name'] . ', We are aware of your concerns',

        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.customer-care',
            with: [
                'first_name' => $this->data['first_name'],
                'review' => $this->data['review'],
                'phone' => $this->data['phone'],
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

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
