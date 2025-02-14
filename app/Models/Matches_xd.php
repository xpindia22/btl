<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matches_xd extends Model
{
    // Specify the database table name (adjust if necessary)
    protected $table = 'matches';

    // Disable automatic timestamps if your table doesn't use created_at/updated_at
    public $timestamps = false;

    // Define the fillable fields for mixed doubles matches
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
        'created_by',
    ];

    /**
     * Get the tournament associated with the match.
     */
    public function tournament()
    {
        return $this->belongsTo(\App\Models\Tournament::class);
    }

    /**
     * Get the category associated with the match.
     */
    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class);
    }

    /**
     * Get the first player of Team 1.
     */
    public function team1Player1()
    {
        return $this->belongsTo(\App\Models\Player::class, 'team1_player1_id');
    }

    /**
     * Get the second player of Team 1.
     */
    public function team1Player2()
    {
        return $this->belongsTo(\App\Models\Player::class, 'team1_player2_id');
    }

    /**
     * Get the first player of Team 2.
     */
    public function team2Player1()
    {
        return $this->belongsTo(\App\Models\Player::class, 'team2_player1_id');
    }

    /**
     * Get the second player of Team 2.
     */
    public function team2Player2()
    {
        return $this->belongsTo(\App\Models\Player::class, 'team2_player2_id');
    }
}
