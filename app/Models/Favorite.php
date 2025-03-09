<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'favoritable_id', 'favoritable_type'];

    public $timestamps = false; // ✅ Prevents Laravel from inserting `updated_at` and `created_at`
}
