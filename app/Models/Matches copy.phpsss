<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Matches extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'matches';

    protected $fillable = [
        'tournament_id',
        'category_id',
        'match_date',
        'match_time',
        'stage',
        'player1_id',
        'player2_id',
        'team1_player1_id',
        'team1_player2_id',
        'team2_player1_id',
        'team2_player2_id',
        'set1_player1_points',
        'set1_player2_points',
        'set2_player1_points',
        'set2_player2_points',
        'set3_player1_points',
        'set3_player2_points',
        'set1_team1_points',
        'set1_team2_points',
        'set2_team1_points',
        'set2_team2_points',
        'set3_team1_points',
        'set3_team2_points',
        'created_by',
        'moderated_by',
        'created_at',
        'updated_at',
    ];
    

    public $timestamps = true;

    // Relationships
    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function player1()
    {
        return $this->belongsTo(Player::class, 'player1_id');
    }

    public function player2()
    {
        return $this->belongsTo(Player::class, 'player2_id');
    }

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

    // Get match details
    public function getSinglesPlayersAttribute()
    {
        return "{$this->player1->name} vs {$this->player2->name}";
    }

    public function getDoublesPlayersAttribute()
    {
        return "{$this->team1Player1->name} & {$this->team1Player2->name} vs {$this->team2Player1->name} & {$this->team2Player2->name}";
    }

    // Query Scopes
    public function scopeSingles($query)
    {
        return $query->whereNotNull('player1_id')->whereNotNull('player2_id');
    }

    public function scopeDoubles($query)
    {
        return $query->whereNotNull('team1_player1_id')
                     ->whereNotNull('team1_player2_id')
                     ->whereNotNull('team2_player1_id')
                     ->whereNotNull('team2_player2_id');
    }
    public function index()
{
    $tournaments = Tournament::all();
    $players = Player::all();
    $matches = Match::all(); // Define $matches here

    return view('matches.singles.index', compact('tournaments', 'players', 'matches'));
}
}
