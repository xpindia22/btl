<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $expiresAt;

    /**
     * Create a new message instance.
     *
     * @param  string  $token
     * @param  \Illuminate\Support\Carbon  $expiresAt
     */
    public function __construct($token, $expiresAt)
    {
        $this->token = $token;
        $this->expiresAt = $expiresAt;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Your Password Reset Link')
                    ->view('emails.reset_password')
                    ->with([
                        'token' => $this->token,
                        'expiresAt' => $this->expiresAt,
                    ]);
    }
}
