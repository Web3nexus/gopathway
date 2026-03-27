<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ScholarshipFoundNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $summary;

    /**
     * Create a new message instance.
     */
    public function __construct(array $summary)
    {
        $this->summary = $summary;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('New Scholarships Discovered')
                    ->markdown('emails.scholarships_found');
    }
}
