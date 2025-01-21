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

class CustomerCare extends Mailable {
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
                new Address('theguru@ravereviewguru.com', 'The Review Guru'),
            ],
            subject: $this->data['first_name'].', Your concerns have been forwarded to '.$this->data['company'].'\'s customer care',

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
