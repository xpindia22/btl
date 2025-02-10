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
        'role', // Role as a string
        'created_by', // User who created this user
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
     * Check if user is a regular user.
     */
    public function isUser()
    {
        return strtolower($this->role) === 'user';
    }

    /**
     * Check if user is a player.
     */
    public function isPlayer()
    {
        return strtolower($this->role) === 'player';
    }
}
