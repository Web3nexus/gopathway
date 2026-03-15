<?php

namespace App\Mail;

use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DynamicEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $templateKey;
    public $data;

    /**
     * Create a new message instance.
     */
    public function __construct(string $templateKey, array $data = [])
    {
        $this->templateKey = $templateKey;
        $this->data = $data;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $template = EmailTemplate::where('key', $this->templateKey)->first();
        $subject = $template ? $template->subject : 'GoPathway Notification';

        // Replace placeholders in subject
        foreach ($this->data as $key => $value) {
            $subject = str_replace('{{' . $key . '}}', $value, $subject);
        }

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $template = EmailTemplate::where('key', $this->templateKey)->first();
        $content = $template ? $template->content : 'You have a new notification.';

        // Replace placeholders in content
        foreach ($this->data as $key => $value) {
            $content = str_replace('{{' . $key . '}}', $value, $content);
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
