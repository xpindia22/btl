<?php
namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserEditedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user; // Hold user data
    public $updatedBy; // Hold the updater's info

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, $updatedBy)
    {
        $this->user = $user;
        $this->updatedBy = $updatedBy;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Your Account Details Have Been Updated') // Updated Subject
                    ->view('emails.user_edited')
                    ->with([
                        'username' => $this->user->username,
                        'email' => $this->user->email,
                        'updatedBy' => $this->updatedBy,
                    ]);
    }
}
