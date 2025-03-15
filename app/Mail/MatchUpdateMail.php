<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MatchUpdateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $match;

    public function __construct($match)
    {
        $this->match = $match;
    }

    public function build()
    {
        // For example, if you want the “createdBy” user:
        $user = $this->match->createdBy;
    
        return $this
            ->subject('Doubles Match Updated with match ID: ' . $this->match->id)
            ->view('emails.match_updated', [
                'match' => $this->match,
                'user' => $user,
            ]);
    }
    
}
