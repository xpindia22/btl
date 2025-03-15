<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentPendingNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $player, $tournament, $categories, $totalFee, $isAdmin;

    public function __construct($player, $tournament, $categories, $totalFee, $isAdmin = false)
    {
        $this->player = $player;
        $this->tournament = $tournament;
        $this->categories = $categories;
        $this->totalFee = $totalFee;
        $this->isAdmin = $isAdmin;
    }

    public function build()
    {
        return $this->subject($this->isAdmin ? "Pending Payment Alert" : "Payment Required for Tournament")
            ->view('emails.payment_pending');
    }
}
