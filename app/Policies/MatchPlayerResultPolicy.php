<?php

namespace App\Policies;

use App\Models\Match;
use App\Models\Player;
use App\Models\Result;
use App\Models\User;

class MatchPlayerResultPolicy
{
    /**
     * Determine if the user can view any matches.
     */
    public function viewAnyMatches(User $user)
    {
        return in_array($user->role, ['admin', 'user', 'player']);
    }

    /**
     * Determine if the user can create a match.
     */
    public function createMatch(User $user)
    {
        return $user->role === 'admin';
    }

    /**
     * Determine if the user can delete a match.
     */
    public function deleteMatch(User $user, Match $match)
    {
        return $user->role === 'admin';
    }

    /**
     * Determine if the user can view player profiles.
     */
    public function viewAnyPlayers(User $user)
    {
        return in_array($user->role, ['admin', 'user', 'player']);
    }

    /**
     * Determine if the user can manage a player.
     */
    public function managePlayer(User $user, Player $player)
    {
        return $user->role === 'admin' || ($user->role === 'player' && $user->id === $player->user_id);
    }

    /**
     * Determine if the user can view results.
     */
    public function viewAnyResults(User $user)
    {
        return in_array($user->role, ['admin', 'user', 'player']);
    }

    /**
     * Determine if the user can update results.
     */
    public function updateResults(User $user, Result $result)
    {
        return $user->role === 'admin';
    }
}
