<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TournamentNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $tournament;
    public $action;
    public $initiator;

    public function __construct($tournament, $action, $initiator)
    {
        $this->tournament = $tournament;
        $this->action = $action;
        $this->initiator = $initiator;
    }

    public function build()
    {
        return $this->subject("Tournament {$this->action} Notification")
                    ->view('emails.tournament_notification')
                    ->with([
                        'tournament' => $this->tournament,
                        'action' => $this->action,
                        'initiator' => $this->initiator
                    ]);
    }
}
