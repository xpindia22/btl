<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchDoubles extends Model
{
    // The underlying table for both singles and doubles matches.
    protected $table = 'matches';

    // Disable automatic timestamps since your table doesn't include created_at/updated_at columns.
    public $timestamps = false;

    // Define fillable fields for doubles matches.
    protected $fillable = [
        'tournament_id',
        'category_id',
        'team1_player1_id',
        'team1_player2_id',
        'team2_player1_id',
        'team2_player2_id',
        'stage',
        'match_date',
        'match_time',
        'set1_team1_points',
        'set1_team2_points',
        'set2_team1_points',
        'set2_team2_points',
        'set3_team1_points',
        'set3_team2_points',
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

    /**
     * Get the first player of Team 1.
     */
    public function team1Player1()
    {
        return $this->belongsTo(Player::class, 'team1_player1_id');
    }

    /**
     * Get the second player of Team 1.
     */
    public function team1Player2()
    {
        return $this->belongsTo(Player::class, 'team1_player2_id');
    }

    /**
     * Get the first player of Team 2.
     */
    public function team2Player1()
    {
        return $this->belongsTo(Player::class, 'team2_player1_id');
    }

    /**
     * Get the second player of Team 2.
     */
    public function team2Player2()
    {
        return $this->belongsTo(Player::class, 'team2_player2_id');
    }
}
