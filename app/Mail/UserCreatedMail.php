<?php
 

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $createdBy;

    public function __construct($user, $createdBy)
    {
        $this->user = $user;
        $this->createdBy = $createdBy;
    }

    public function build()
    {
        return $this->subject("New User {$this->user->username} Registered")
                    ->view('emails.user_created')
                    ->with([
                        'user' => $this->user,
                        'createdBy' => $this->createdBy,
                    ]);
    }
}
