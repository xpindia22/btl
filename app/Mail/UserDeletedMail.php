<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserDeletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $deletedUserDetails;

    /**
     * Create a new message instance.
     */
    public function __construct($deletedUserDetails)
    {
        $this->deletedUserDetails = $deletedUserDetails;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject("User {$this->deletedUserDetails['Username']} Deleted")
                    ->view('emails.user_deleted')
                    ->with([
                        'deletedUserDetails' => $this->deletedUserDetails
                    ]);
    }
}
