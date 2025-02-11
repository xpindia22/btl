<?php 
// app/Models/Tournament.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    protected $fillable = ['name', 'year', 'created_by'];
}
