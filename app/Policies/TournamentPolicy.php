<?php

namespace App\Policies;

use App\Models\Tournament;
use App\Models\User;

class TournamentPolicy
{
    /**
     * Determine if the user can edit the tournament.
     */
    public function editTournament(User $user, Tournament $tournament)
    {
        return $user->role === 'admin' || $tournament->created_by === $user->id;
    }

    /**
     * Determine if the user can delete the tournament.
     */
    public function deleteTournament(User $user, Tournament $tournament)
    {
        return $user->role === 'admin' || $tournament->created_by === $user->id;
    }
}
