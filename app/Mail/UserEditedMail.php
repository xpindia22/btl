<?php
namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserEditedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $updatedBy;
    public $updatedFields;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, $updatedBy, $updatedFields)
    {
        $this->user = $user;
        $this->updatedBy = $updatedBy;
        $this->updatedFields = $updatedFields;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject("User {$this->user->username} - Account Updated")
                    ->view('emails.user_edited')
                    ->with([
                        'user' => $this->user,
                        'updatedBy' => $this->updatedBy,
                        'updatedFields' => $this->updatedFields,
                        'moderatedTournaments' => $this->user->moderatedTournaments,
                        'createdTournaments' => $this->user->createdTournaments,
                    ]);
    }
}
