<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash; // Ensure Hash is imported

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'uid', 'name', 'dob', 'sex', 'email', 'secondary_email',
        'password', 'secret_question1', 'secret_question2', 'secret_question3',
        'age', 'ip_address'
    ];

    /**
     * Automatically hash secret question 1 before storing
     */
    public function setSecretQuestion1Attribute($value)
    {
        $this->attributes['secret_question1'] = Hash::make(strtolower($value));
    }

    /**
     * Automatically hash secret question 2 before storing
     */
    public function setSecretQuestion2Attribute($value)
    {
        $this->attributes['secret_question2'] = Hash::make(strtolower($value));
    }

    /**
     * Automatically hash secret question 3 before storing
     */
    public function setSecretQuestion3Attribute($value)
    {
        $this->attributes['secret_question3'] = Hash::make(strtolower($value));
    }
}
