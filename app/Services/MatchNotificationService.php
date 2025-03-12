<?php

namespace App\Services;

use App\Mail\MatchCreatedMail;
use Illuminate\Support\Facades\Mail;

class MatchNotificationService
{
    /**
     * Send an email notification when a match is created.
     *
     * @param \App\Models\Match $match
     * @return void
     */
    public function sendMatchCreatedNotification($match)
    {
        $creatorEmail = $match->createdBy->email;
        $playerEmails = $match->players->pluck('email')->toArray();
        $adminEmail   = 'xpindia@gmail.com';

        // Merge all recipients while removing duplicates.
        $recipients = array_unique(array_merge([$creatorEmail], $playerEmails, [$adminEmail]));

        // Send the email notification using Laravel's Mail facade.
        Mail::to($recipients)->send(new MatchCreatedMail($match));
    }
}
