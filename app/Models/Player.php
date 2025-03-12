<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    // Disable timestamps if you're not using them
    public $timestamps = false;

    protected $fillable = [
        'uid',
        'name',
        'email',
        'mobile',
        'dob',
        'sex',
        'ip_address',
        'password',
    ];

    // Cast the dob field to a date instance (Carbon)
    protected $casts = [
        'dob' => 'date',
    ];

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }

    public function isFavoritedByUser($userId)
    {
        return $this->favorites()->where('user_id', $userId)->exists();
    }
}
