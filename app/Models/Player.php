<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    // If you are not using timestamps for updated_at, you can disable them
    public $timestamps = false;

    protected $fillable = ['uid', 'name', 'dob', 'age', 'sex', 'password'];
}
