<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MatchPinnedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $match;
    public $action; // "pinned" or "unpinned"

    public function __construct($user, $match, $action)
    {
        $this->user = $user;
        $this->match = $match;
        $this->action = $action;
    }

    public function build()
    {
        return $this->subject("Match {$this->action} Notification")
                    ->view('emails.match_pinned')
                    ->with([
                        'user' => $this->user,
                        'match' => $this->match,
                        'action' => $this->action,
                    ]);
    }
}
