<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserDeletedMail extends Mailable
{
    use Queueable, SerializesModels; // ✅ This is a trait

    public $deletedUserDetails;

    public function __construct($deletedUserDetails)
    {
        $this->deletedUserDetails = $deletedUserDetails;
    }

    public function build()
    {
        return $this->subject("User Deleted: {$this->deletedUserDetails['Username']}")
                    ->view('emails.user_deleted')
                    ->with([
                        'deletedUserDetails' => $this->deletedUserDetails,
                    ]);
    }
}
