<?php

namespace App\Mail;

use App\Models\Message;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SupportNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $sender;
    public $messageObj;
    public $type; // 'new_support_ticket' or 'admin_reply'

    /**
     * Create a new message instance.
     */
    public function __construct(User $sender, Message $messageObj, string $type)
    {
        $this->sender = $sender;
        $this->messageObj = $messageObj;
        $this->type = $type;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->type === 'new_support_ticket' 
            ? "[Support] New Message: " . ($this->messageObj->conversation->subject ?? 'No Subject')
            : "Re: [Support] " . ($this->messageObj->conversation->subject ?? 'Support Message');

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.support.notification',
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
