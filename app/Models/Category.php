<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // Other model properties and methods...

    /**
     * Get the tournaments associated with this category.
     */
    public function tournaments()
    {
        return $this->belongsToMany(Tournament::class, 'tournament_categories', 'category_id', 'tournament_id');
    }

    public function favorites()
{
    return $this->morphMany(Favorite::class, 'favoritable');
}

public function isFavoritedByUser($userId)
{
    return $this->favorites()->where('user_id', $userId)->exists();
}

}
