<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'email',
        'password',
        'mobile_no',
        'role',
        'created_by',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'created_by' => 'integer',
    ];

    /**
     * Get the user who created this user.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get users created by this user.
     */
    public function createdUsers()
    {
        return $this->hasMany(User::class, 'created_by');
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin()
    {
        return strtolower($this->role) === 'admin';
    }

    /**
     * Get tournaments the user moderates (both direct assignment and through the tournament_moderators table).
     */
    public function moderatedTournaments()
    {
        return $this->belongsToMany(Tournament::class, 'tournament_moderators', 'user_id', 'tournament_id')
                    ->distinct();
    }
    

    /**
     * Get tournaments the user created.
     */
    public function createdTournaments()
    {
        return $this->hasMany(Tournament::class, 'created_by');
    }

    /**
     * Check if the user can moderate a match.
     */
    public function canModerateMatch($match)
{
    // If user is the assigned match moderator
    if ($this->id === $match->moderated_by) {
        return true;
    }

    // If user is a tournament moderator ( created_by or moderated_by in match table will work.)
    if ($this->moderatedTournaments()->where('id', $match->tournament_id)->exists()) {
        return true;
    }

    // OPTIONAL: Allow creators to edit only if no moderator is assigned
    if ($this->id === $match->created_by && !$match->moderated_by) {
        return true;
    }

    return false;
}

}
