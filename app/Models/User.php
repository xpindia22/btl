<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'username',
        'email',
        'password',
        'mobile_no',
        'role', // ✅ Keep role as a string
        'created_by'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // ✅ Relationship: Get the creator of this user
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }

    public function isPlayer()
    {
        return $this->role === 'player';
    }
}
