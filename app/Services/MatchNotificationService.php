<?php

namespace App\Services;

use App\Mail\MatchCreatedMail;
use App\Mail\MatchUpdatedNotification; // ✅ Use the existing notification
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\Matches;
use Illuminate\Support\Facades\Auth;

class MatchNotificationService
{
    /**
     * Send an email notification when a match is created.
     *
     * @param \App\Models\Matches $match
     * @return void
     */
    public function sendMatchCreatedNotification($match)
    {
        $this->sendMatchNotification($match, new MatchCreatedMail($match), "Match Created");
    }

    /**
     * Send an email notification when a match is updated.
     *
     * @param \App\Models\Matches $match
     * @return void
     */
    public function sendMatchUpdatedNotification($match)
    {
        // Fetch old match data before changes
        $originalMatch = $match->getOriginal();

        // Identify changes
        $changes = $match->getChanges();

        if (empty($changes)) {
            Log::info("ℹ No changes detected for Match ID: {$match->id}. Skipping notification.");
            return;
        }

        $user = Auth::user(); // Logged-in user who made the update
        $creatorEmail = optional($match->createdBy)->email;
        $playerEmails = [$match->player1->email, $match->player2->email];
        $adminEmail   = 'xpindia@gmail.com';

        // Remove empty/null values and duplicates
        $recipients = array_unique(array_filter(array_merge([$creatorEmail], $playerEmails, [$adminEmail])));

        if (!empty($recipients)) {
            Mail::to($recipients)->queue(new MatchUpdatedNotification($user, $match, $changes));
            Log::info("📩 Match Update Notification Sent to: " . implode(", ", $recipients));
        } else {
            Log::warning("⚠ No valid email recipients for match update notification.");
        }
    }

    /**
     * Generic function to send match notifications.
     *
     * @param \App\Models\Matches $match
     * @param \Illuminate\Mail\Mailable $mailInstance
     * @param string $logMessage
     * @return void
     */
    private function sendMatchNotification($match, $mailInstance, $logMessage)
    {
        $creatorEmail = optional($match->createdBy)->email;
        $playerEmails = [$match->player1->email, $match->player2->email];
        $adminEmail   = 'xpindia@gmail.com';

        // Remove null/empty values and duplicates
        $recipients = array_unique(array_filter(array_merge([$creatorEmail], $playerEmails, [$adminEmail])));

        if (!empty($recipients)) {
            Mail::to($recipients)->queue($mailInstance);
            Log::info("📩 {$logMessage} Notification Sent to: " . implode(", ", $recipients));
        } else {
            Log::warning("⚠ No valid email recipients for {$logMessage} notification.");
        }
    }
}
