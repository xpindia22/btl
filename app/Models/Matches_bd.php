<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matches_bd extends Model
{
    protected $table = 'matches';

    public $timestamps = false;

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

    public function tournament()
    {
        return $this->belongsTo(\App\Models\Tournament::class);
    }

    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class);
    }

    public function team1Player1()
    {
        return $this->belongsTo(\App\Models\Player::class, 'team1_player1_id');
    }

    public function team1Player2()
    {
        return $this->belongsTo(\App\Models\Player::class, 'team1_player2_id');
    }

    public function team2Player1()
    {
        return $this->belongsTo(\App\Models\Player::class, 'team2_player1_id');
    }

    public function team2Player2()
    {
        return $this->belongsTo(\App\Models\Player::class, 'team2_player2_id');
    }
}
