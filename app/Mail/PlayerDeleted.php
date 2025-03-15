<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PlayerDeleted extends Mailable
{
    use Queueable, SerializesModels;

    public $playerName;
    public $deletedBy;

    public function __construct($playerName, $deletedBy)
    {
        $this->playerName = $playerName;
        $this->deletedBy = $deletedBy;
    }

    public function build()
    {
        return $this->subject('Player Deleted Notification')
                    ->view('emails.player_deleted')
                    ->with([
                        'playerName' => $this->playerName,
                        'deletedBy' => $this->deletedBy
                    ]);
    }
}
