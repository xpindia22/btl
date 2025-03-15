<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Tournament;
use App\Models\User;

class PaymentNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $tournament;
    public $amount;

    public function __construct(User $user, Tournament $tournament)
    {
        $this->user = $user;
        $this->tournament = $tournament;
        $this->amount = $tournament->tournament_fee;
    }

    public function build()
    {
        return $this->subject('Payment Required for Tournament Participation')
                    ->view('emails.payment_notification');
    }
}
