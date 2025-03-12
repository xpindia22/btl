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
     * Determine if the match is a doubles match.
     *
     * @return bool
     */
    protected function isDoubles()
    {
        return $this->matches->team1_player1_id &&
               $this->matches->team1_player2_id &&
               $this->matches->team2_player1_id &&
               $this->matches->team2_player2_id;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        if ($this->isDoubles()) {
            return $this->subject('New Doubles Match Created')
                        ->markdown('emails.doubles_match_created')
                        ->with([
                            'matchType' => 'doubles'
                        ]);
        } else {
            return $this->subject('New Singles Match Created')
                        ->markdown('emails.match_created')
                        ->with([
                            'matchType' => 'singles'
                        ]);
        }
    }
}
