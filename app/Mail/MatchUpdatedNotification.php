<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MatchUpdatedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $match;
    public $changes;

    public function __construct($user, $match, $changes)
    {
        $this->user = $user;
        $this->match = $match;
        $this->changes = $changes;
    }

    public function build()
    {
        return $this->subject("Match Updated Notification")
                    ->view('emails.match_updated')
                    ->with([
                        'user' => $this->user,
                        'match' => $this->match,
                        'changes' => $this->changes,
                    ]);
    }
}
