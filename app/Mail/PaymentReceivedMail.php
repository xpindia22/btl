<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Payment;
use App\Models\User;
use App\Models\Tournament;

class PaymentReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $payment;
    public $player;
    public $tournament;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
        $this->player = $payment->user;
        $this->tournament = $payment->tournament;
    }

    public function build()
    {
        return $this->subject('Payment Received for ' . $this->tournament->name)
                    ->view('emails.payment_received');
    }
}
