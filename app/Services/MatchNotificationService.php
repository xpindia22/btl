<?php

namespace App\Services;

use App\Mail\MatchCreatedMail;
use App\Mail\MatchUpdatedNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\Matches;
use App\Models\User;
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

        $this->sendMatchNotification($match, new MatchUpdatedNotification($user, $match, $changes), "Match Updated");
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
        // Get admin users
        $admins = User::where('role', 'admin')->pluck('email')->toArray();

        // Get match creator
        $creatorEmail = optional($match->createdBy)->email;

        // Get players involved in the match (handles singles & doubles)
        $playerEmails = [];
        if (!empty($match->player1)) $playerEmails[] = $match->player1->email;
        if (!empty($match->player2)) $playerEmails[] = $match->player2->email;
        if (!empty($match->player3)) $playerEmails[] = $match->player3->email;
        if (!empty($match->player4)) $playerEmails[] = $match->player4->email;

        // Get moderators assigned to the tournament
        $moderators = $match->tournament->moderators()->pluck('email')->toArray();

        // Merge all recipients and remove duplicates
        $recipients = array_filter(array_unique(array_merge($admins, [$creatorEmail], $playerEmails, $moderators)));

        if (!empty($recipients)) {
            Mail::to($recipients)->queue($mailInstance);
            Log::info("📩 {$logMessage} Notification Sent to: " . implode(", ", $recipients));
        } else {
            Log::warning("⚠ No valid email recipients for {$logMessage} notification.");
        }
    }
}
