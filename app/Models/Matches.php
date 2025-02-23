<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Matches extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tournament_id', 'category_id', 'stage', 'match_date', 'match_time',
        'player1_id', 'player2_id',  // Singles players
        'team1_player1_id', 'team1_player2_id', 'team2_player1_id', 'team2_player2_id', // Doubles players
        'set1_team1_points', 'set1_team2_points',
        'set2_team1_points', 'set2_team2_points',
        'set3_team1_points', 'set3_team2_points',
        'winner', 'created_by', 'moderated_by'
    ];

    /**
     * Get the tournament associated with the match.
     */
    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    /**
     * Get the category of the match.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // --- Relationships for Singles Players ---
    public function player1()
    {
        return $this->belongsTo(Player::class, 'player1_id');
    }

    public function player2()
    {
        return $this->belongsTo(Player::class, 'player2_id');
    }

    // --- Relationships for Doubles Players ---
    public function team1Player1()
    {
        return $this->belongsTo(Player::class, 'team1_player1_id');
    }

    public function team1Player2()
    {
        return $this->belongsTo(Player::class, 'team1_player2_id');
    }

    public function team2Player1()
    {
        return $this->belongsTo(Player::class, 'team2_player1_id');
    }

    public function team2Player2()
    {
        return $this->belongsTo(Player::class, 'team2_player2_id');
    }

    /**
     * Get the user who created the match.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who moderates the match.
     */
    public function moderator()
    {
        return $this->belongsTo(User::class, 'moderated_by');
    }

    /**
     * Check if a user can moderate this match.
     */
    public function canBeModeratedBy($user)
    {
        return $user->id === $this->moderated_by || 
               $user->moderatedTournaments()->where('id', $this->tournament_id)->exists();
    }
}
