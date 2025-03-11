<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Matches; // ✅ Correct namespace
use App\Models\User;

class MatchUpdatedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $match;
    public $changes;

    public function __construct($user, Matches $match, $changes)
    {
        $this->user = $user;
        $this->match = $match->fresh(); // ✅ Ensure latest DB values
        $this->changes = $changes;
    }

    public function build()
{
    return $this->subject('Match Update Notification')
                ->view('emails.match_updated')
                ->with([
                    'user' => $this->user,
                    'match' => $this->match,
                    'changes' => $this->changes // ✅ Ensure changes are passed to Blade
                ]);
}

}
