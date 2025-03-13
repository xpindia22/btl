<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'email',
        'password',
        'dob',
        'sex',
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
     * 🔥 Role Helper Methods 🔥
     * Checks if a user has a specific role.
     */
    public function isAdmin()
    {
        return strtolower($this->role) === 'admin';
    }

    public function isUser()
    {
        return strtolower($this->role) === 'user';
    }

    public function isPlayer()
    {
        return strtolower($this->role) === 'player';
    }

    public function isVisitor()
    {
        return strtolower($this->role) === 'visitor';
    }

    /**
     * ✅ Get the user who created this user.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * ✅ Get users created by this user.
     */
    public function createdUsers(): HasMany
    {
        return $this->hasMany(User::class, 'created_by');
    }

    /**
     * ✅ Get tournaments where the user is a **moderator**.
     */
    public function moderatedTournaments()
{
    return $this->belongsToMany(Tournament::class, 'tournament_moderators', 'user_id', 'tournament_id')
                ->distinct();
}

    /**
     * ✅ Get tournaments where the user is the **creator**.
     */
    public function createdTournaments()
{
    return $this->hasMany(Tournament::class, 'created_by');
}
    /**
     * ✅ Attribute to get the count of created tournaments.
     */
    public function getCreatedTournamentsCountAttribute(): int
    {
        return $this->createdTournaments()->count();
    }

    /**
     * ✅ Attribute to get the count of moderated tournaments.
     */
    public function getModeratedTournamentsCountAttribute(): int
    {
        return $this->moderatedTournaments()->count();
    }

    /**
     * ✅ Get matches moderated by this user.
     */
    public function moderatedMatches(): HasMany
    {
        return $this->hasMany(Matches::class, 'moderated_by');
    }

    /**
     * 🔥 Check if the user can **moderate a match**.
     */
    public function canModerateMatch($match): bool
    {
        if ($this->id === $match->moderated_by) {
            return true;
        }

        if ($this->moderatedTournaments()->where('id', $match->tournament_id)->exists()) {
            return true;
        }

        // Allow creators to edit only if no moderator is assigned
        if ($this->id === $match->created_by && !$match->moderated_by) {
            return true;
        }

        return false;
    }

    /**
     * ✅ Get the user's favorites.
     */
    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class, 'user_id');
    }
}
