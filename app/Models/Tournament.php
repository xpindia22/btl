<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tournament extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'created_by', 'year', 'moderated_by', 'ip_address'];

    /**
     * Get the moderators assigned to the tournament.
     */
    public function moderators()
    {
        return $this->belongsToMany(User::class, 'tournament_moderators', 'tournament_id', 'user_id')
                    ->distinct();
    }

    /**
     * Get matches associated with this tournament.
     */
    public function matches()
    {
        return $this->hasMany(Matches::class, 'tournament_id');
    }

    /**
     * Get the user who created the tournament.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    protected static function boot()
{
    parent::boot();

    static::creating(function ($tournament) {
        if (empty($tournament->created_by)) {
            $tournament->created_by = auth()->id(); // Set logged-in user as creator
        }
    });
}

public function favorites()
{
    return $this->morphMany(Favorite::class, 'favoritable');
}

public function isFavoritedByUser($userId)
{
    return $this->favorites()->where('user_id', $userId)->exists();
}


}
