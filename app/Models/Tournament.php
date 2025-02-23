<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    protected $fillable = ['name', 'created_by', 'year', 'moderated_by', 'ip_address'];

    /**
     * Get the moderators that belong to the tournament.
     */
    public function moderators()
    {
        return $this->belongsToMany(
            \App\Models\User::class,    // Adjust the namespace if needed
            'tournament_moderators',    // Pivot table name
            'tournament_id',            // Foreign key for Tournament in pivot
            'user_id'                   // Foreign key for User in pivot
        );
    }

    public function matches()
    {
        return $this->hasMany(Matches::class, 'tournament_id');
    }
    


    // Other relationships or methods...
}
