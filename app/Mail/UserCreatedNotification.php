<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserCreatedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $recipientType;
    public $action;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, $recipientType, $action)
    {
        $this->user = $user;
        $this->recipientType = $recipientType;
        $this->action = $action;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $subject = match ($this->action) {
            'created' => 'New User Created Notification',
            'updated' => 'User Account Updated Notification',
            default => 'User Account Notification',
        };

        return $this->subject($subject)
                    ->view('emails.user_notification')
                    ->with([
                        'user' => $this->user,
                        'recipientType' => $this->recipientType,
                        'action' => $this->action,
                    ]);
    }
}
