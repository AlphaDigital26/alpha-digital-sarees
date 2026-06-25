<?php

namespace App\Mail;

use App\Models\UserQuery;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminContactInquiryMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $query;

    /**
     * Create a new message instance.
     */
    public function __construct(UserQuery $query)
    {
        $this->query = $query;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('contact@adsarees.com', 'Alpha Digital Sarees'),
            subject: 'New Contact Inquiry: ' . $this->query->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.admin.contact_inquiry',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
