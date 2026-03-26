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
        $subject = $template ? $template->subject : $this->getDefaultSubject();

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
        $content = $template ? $template->content : $this->getDefaultContent();

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

    /**
     * Default subject fallback for critical templates.
     */
    protected function getDefaultSubject(): string
    {
        return match ($this->templateKey) {
            'email_verification' => 'Verify Your Email Address',
            'password_reset' => 'Password Reset Request',
            'welcome_email' => 'Welcome to GoPathway',
            default => 'GoPathway Notification',
        };
    }

    /**
     * Default content fallback for critical templates.
     */
    protected function getDefaultContent(): string
    {
        $buttonStyle = "display: inline-block; padding: 12px 24px; background-color: #0B3C91; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: bold; text-align: center;";
        
        return match ($this->templateKey) {
            'email_verification' => "# Verify Your Email\n\nHello {{user_name}},\n\nPlease click the button below to verify your email address:\n\n<p style=\"text-align: center; margin-top: 20px;\"><a href=\"{{verification_url}}\" style=\"{$buttonStyle}\">Verify Email Address</a></p>",
            'password_reset' => "# Password Reset\n\nHello {{user_name}},\n\nYou requested a password reset. Click the button below to set a new password:\n\n<p style=\"text-align: center; margin-top: 20px;\"><a href=\"{{reset_url}}\" style=\"{$buttonStyle}\">Reset Password</a></p>",
            'welcome_email' => "# Welcome to GoPathway, {{user_name}}!\n\nThank you for joining. We are excited to help you on your relocation journey.\n\n<p style=\"text-align: center; margin-top: 20px;\"><a href=\"{{dashboard_url}}\" style=\"{$buttonStyle}\">Go to Dashboard</a></p>",
            default => 'You have a new notification.',
        };
    }
}
