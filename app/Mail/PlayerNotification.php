<?php

namespace App\Mail;

use App\Models\Player;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PlayerNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $player;
    public $action;
    public $modifiedBy;

    /**
     * Create a new message instance.
     *
     * @param Player $player
     * @param string $action (e.g. 'registered' or 'updated')
     * @param mixed $modifiedBy (the user who initiated the action)
     */
    public function __construct($player, $action, $modifiedBy)
    {
        $this->player = $player;
        $this->action = $action;
        $this->modifiedBy = $modifiedBy;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject("Player " . ucfirst($this->action) . ": " . $this->player->name)
                    ->view('emails.player_notification')
                    ->with([
                        'player'      => $this->player,
                        'action'      => $this->action,
                        'modifiedBy'  => $this->modifiedBy,
                        'adminEmail'  => 'xpindia@gmail.com',
                    ]);
    }
}
