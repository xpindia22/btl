<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matches extends Model
{
    protected $table = 'matches';

    // Disable automatic timestamps since your table doesn't include created_at/updated_at
    public $timestamps = false;

    // Define fillable columns
    protected $fillable = [
        'tournament_id',
        'category_id',
        'player1_id',
        'player2_id',
        'stage',
        'match_date',
        'match_time',
        'set1_player1_points',
        'set1_player2_points',
        'set2_player1_points',
        'set2_player2_points',
        'set3_player1_points',
        'set3_player2_points',
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
     * Get the first player for this match.
     */
    public function player1()
    {
        return $this->belongsTo(Player::class, 'player1_id');
    }

    /**
     * Get the second player for this match.
     */
    public function player2()
    {
        return $this->belongsTo(Player::class, 'player2_id');
    }
}
