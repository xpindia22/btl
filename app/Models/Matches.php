<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matches extends Model
{
    protected $table = 'matches';

    // Disable automatic timestamps since your table doesn't include created_at/updated_at
    public $timestamps = false;

    // Define fillable columns (for both Singles & Doubles)
    protected $fillable = [
        'tournament_id',
        'category_id',
        // Singles
        'player1_id',
        'player2_id',
        'set1_player1_points',
        'set1_player2_points',
        'set2_player1_points',
        'set2_player2_points',
        'set3_player1_points',
        'set3_player2_points',
        // Doubles
        'team1_player1_id',
        'team1_player2_id',
        'team2_player1_id',
        'team2_player2_id',
        'set1_team1_points',
        'set1_team2_points',
        'set2_team1_points',
        'set2_team2_points',
        'set3_team1_points',
        'set3_team2_points',
        // Common fields
        'stage',
        'match_date',
        'match_time',
        'created_by'
    ];

    /**
     * Get the tournament associated with this match.
     */
    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    /**
     * Get the category associated with this match.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // 🏸 **Singles Match Relationships**
    
    /**
     * Get the first player for a singles match.
     */
    public function player1()
    {
        return $this->belongsTo(Player::class, 'player1_id');
    }

    /**
     * Get the second player for a singles match.
     */
    public function player2()
    {
        return $this->belongsTo(Player::class, 'player2_id');
    }

    // 🏸 **Doubles Match Relationships**

    /**
     * Get the first player of Team 1 in a doubles match.
     */
    public function team1Player1()
    {
        return $this->belongsTo(Player::class, 'team1_player1_id');
    }

    /**
     * Get the second player of Team 1 in a doubles match.
     */
    public function team1Player2()
    {
        return $this->belongsTo(Player::class, 'team1_player2_id');
    }

    /**
     * Get the first player of Team 2 in a doubles match.
     */
    public function team2Player1()
    {
        return $this->belongsTo(Player::class, 'team2_player1_id');
    }

    /**
     * Get the second player of Team 2 in a doubles match.
     */
    public function team2Player2()
    {
        return $this->belongsTo(Player::class, 'team2_player2_id');
    }

    // 🏸 **Helper Functions for Easy Access**

    /**
     * Get the full names of players in Team 1 for doubles.
     */
    public function getTeam1Names()
    {
        return trim(($this->team1Player1->name ?? '') . ' & ' . ($this->team1Player2->name ?? '')) ?: 'N/A';
    }

    /**
     * Get the full names of players in Team 2 for doubles.
     */
    public function getTeam2Names()
    {
        return trim(($this->team2Player1->name ?? '') . ' & ' . ($this->team2Player2->name ?? '')) ?: 'N/A';
    }

    /**
     * Get the winner for both singles and doubles matches.
     */
    public function getWinnerAttribute()
    {
        if ($this->player1_id && $this->player2_id) {
            // Singles match winner
            $player1SetsWon = ($this->set1_player1_points > $this->set1_player2_points ? 1 : 0) +
                              ($this->set2_player1_points > $this->set2_player2_points ? 1 : 0) +
                              ($this->set3_player1_points > $this->set3_player2_points ? 1 : 0);

            return ($player1SetsWon >= 2) ? ($this->player1->name ?? 'N/A') : ($this->player2->name ?? 'N/A');
        } else {
            // Doubles match winner
            $team1SetsWon = ($this->set1_team1_points > $this->set1_team2_points ? 1 : 0) +
                            ($this->set2_team1_points > $this->set2_team2_points ? 1 : 0) +
                            ($this->set3_team1_points > $this->set3_team2_points ? 1 : 0);

            return ($team1SetsWon >= 2) ? $this->getTeam1Names() : $this->getTeam2Names();
        }
    }
}
