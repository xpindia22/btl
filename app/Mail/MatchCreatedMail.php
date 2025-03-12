<?php

namespace App\Mail;

use App\Models\Matches;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MatchCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $matches;

    /**
     * Create a new message instance.
     */
    public function __construct(Matches $matches)
    {
        $this->matches = $matches;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('New Match Created')
                    ->markdown('emails.match_created');
    }
}
