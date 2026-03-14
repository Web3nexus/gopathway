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
        $templateKey = $this->type === 'new_support_ticket' ? 'support_notification_admin' : 'support_notification_user';
        $template = \App\Models\EmailTemplate::where('key', $templateKey)->first();

        $subject = $template ? $template->subject : ($this->type === 'new_support_ticket' ? '[Support] New Message' : 'Re: [Support]');
        
        // Replace placeholders in subject
        $subject = str_replace('{{subject}}', $this->messageObj->conversation->subject ?? 'Support Message', $subject);

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $templateKey = $this->type === 'new_support_ticket' ? 'support_notification_admin' : 'support_notification_user';
        $template = \App\Models\EmailTemplate::where('key', $templateKey)->first();

        $content = $template ? $template->content : 'You have a new support message.';
        
        // Replace common placeholders
        $placeholders = [
            '{{user_name}}' => $this->type === 'new_support_ticket' ? $this->sender->name : $this->sender->name,
            '{{user_email}}' => $this->sender->email,
            '{{subject}}' => $this->messageObj->conversation->subject ?? 'N/A',
            '{{message_body}}' => $this->messageObj->body,
            '{{button_url}}' => config('app.frontend_url') . ($this->type === 'new_support_ticket' ? '/admin/support' : '/support'),
        ];

        foreach ($placeholders as $key => $value) {
            $content = str_replace($key, $value, $content);
        }

        return new Content(
            markdown: 'emails.dynamic',
            with: [
                'body' => $content,
            ],
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
