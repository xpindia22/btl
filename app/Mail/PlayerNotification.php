<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PlayerNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $player;
    public $action;
    public $modifiedBy;

    /**
     * Create a new message instance.
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
        return $this->subject("Player {$this->action}: {$this->player->name}")
                    ->view('emails.player_notification')
                    ->with([
                        'player' => $this->player,
                        'action' => $this->action,
                        'modifiedBy' => $this->modifiedBy,
                    ]);
    }
}
