<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matches_xd extends Model
{
    use HasFactory;

    // Specify the database table name
    protected $table = 'matches';

    // Disable timestamps if not using created_at/updated_at columns
    public $timestamps = false;

    // Fillable fields
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
     * Tournament Relationship - Each match belongs to one tournament.
     */
    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    /**
     * Category Relationship - Each match belongs to one category.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Player Relationships for Team 1
     */
    public function team1Player1()
    {
        return $this->belongsTo(Player::class, 'team1_player1_id');
    }

    public function team1Player2()
    {
        return $this->belongsTo(Player::class, 'team1_player2_id');
    }

    /**
     * Player Relationships for Team 2
     */
    public function team2Player1()
    {
        return $this->belongsTo(Player::class, 'team2_player1_id');
    }

    public function team2Player2()
    {
        return $this->belongsTo(Player::class, 'team2_player2_id');
    }
}
