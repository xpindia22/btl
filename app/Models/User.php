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
     * Get tournaments the user moderates.
     */
    public function moderatedTournaments()
    {
        return $this->hasMany(Tournament::class, 'moderated_by');
    }

    /**
     * Get tournaments the user created.
     */
    public function createdTournaments()
    {
        return $this->hasMany(Tournament::class, 'created_by');
    }
}
