<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matches extends Model
{
    protected $table = 'matches';

    // Disable automatic timestamps since your table doesn't include created_at/updated_at
    public $timestamps = false;

    // Optionally, define fillable columns
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
}
