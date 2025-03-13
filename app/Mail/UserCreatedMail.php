<?php
namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $createdBy;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, $createdBy)
    {
        $this->user = $user;
        $this->createdBy = $createdBy;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('New User ' . $this->user->username . ' Created')
                    ->view('emails.user_created')
                    ->with([
                        'user' => $this->user,
                        'createdBy' => $this->createdBy,
                        'moderatedTournaments' => $this->user->moderatedTournaments,
                        'createdTournaments' => $this->user->createdTournaments,
                    ]);
    }
}
