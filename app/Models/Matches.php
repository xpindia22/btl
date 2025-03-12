<?php

namespace App\Models;
use App\Models\User; // add this at the top with your other imports
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\Favorite;
use App\Mail\MatchUpdatedNotification;

class Matches extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'matches';

    protected $fillable = [
        'tournament_id',
        'category_id',
        'match_date',
        'match_time',
        'stage',
        'player1_id',
        'player2_id',
        'team1_player1_id',
        'team1_player2_id',
        'team2_player1_id',
        'team2_player2_id',
        'set1_player1_points',
        'set1_player2_points',
        'set2_player1_points',
        'set2_player2_points',
        'set3_player1_points',
        'set3_player2_points',
        'set1_team1_points',
        'set1_team2_points',
        'set2_team1_points',
        'set2_team2_points',
        'set3_team1_points',
        'set3_team2_points',
        'created_by',
        'moderated_by',
        'created_at',
        'updated_at',
        'moderator',
        'creator'
    ];

    public $timestamps = true;

    // Ensure data types are properly handled
    protected $casts = [
        'set1_player1_points' => 'integer',
        'set1_player2_points' => 'integer',
        'set2_player1_points' => 'integer',
        'set2_player2_points' => 'integer',
        'set3_player1_points' => 'integer',
        'set3_player2_points' => 'integer',
        'set1_team1_points' => 'integer',
        'set1_team2_points' => 'integer',
        'set2_team1_points' => 'integer',
        'set2_team2_points' => 'integer',
        'set3_team1_points' => 'integer',
        'set3_team2_points' => 'integer',
    ];
    

    // Relationships
    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function player1()
    {
        return $this->belongsTo(Player::class, 'player1_id');
    }

    public function player2()
    {
        return $this->belongsTo(Player::class, 'player2_id');
    }

    public function team1Player1()
    {
        return $this->belongsTo(Player::class, 'team1_player1_id');
    }

    public function team1Player2()
    {
        return $this->belongsTo(Player::class, 'team1_player2_id');
    }

    public function team2Player1()
    {
        return $this->belongsTo(Player::class, 'team2_player1_id');
    }

    public function team2Player2()
    {
        return $this->belongsTo(Player::class, 'team2_player2_id');
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }

    public function isFavoritedByUser($userId)
    {
        return $this->favorites()->where('user_id', $userId)->exists();
    }

    /**
     * Hook into model update to send email notifications
     */
//     protected static function boot()
// {
//     parent::boot();

//     static::updated(function ($match) {
//         Log::info("🔄 Match update detected for ID: {$match->id}");

//         $columnsToCheck = [
//             'stage', 'match_date', 'match_time',
//             'set1_player1_points', 'set1_player2_points',
//             'set2_player1_points', 'set2_player2_points',
//             'set3_player1_points', 'set3_player2_points'
//         ];

//         $changes = [];

//         foreach ($columnsToCheck as $column) {
//             $oldValue = (string) $match->getOriginal($column); // Convert old value to string
//             $newValue = (string) $match->{$column};  // Convert new value to string

//             if ($oldValue !== $newValue) {
//                 $changes[$column] = [
//                     'old' => $oldValue,
//                     'new' => $newValue
//                 ];
//             }
//         }

//         if (!empty($changes)) {
//             Log::info("🔔 Significant changes detected for Match ID: {$match->id}", $changes);

//             $favoritedByUsers = Favorite::where('favoritable_id', $match->id)
//                 ->where('favoritable_type', Matches::class)
//                 ->pluck('user_id')
//                 ->unique();

//             collect($favoritedByUsers)->each(function ($userId) use ($match, $changes) {
//                 $user = \App\Models\User::find($userId);

//                 if ($user) {
//                     try {
//                         Log::info("📨 Sending email to {$user->email} for Match ID: {$match->id}");
//                         Mail::to($user->email)->queue(new MatchUpdatedNotification($user, $match, $changes));
//                         Log::info("✅ Email successfully queued to: {$user->email}");
//                     } catch (\Exception $e) {
//                         Log::error("❌ Email sending failed for {$user->email}: " . $e->getMessage());
//                     }
//                 }
//             });
//         } else {
//             Log::info("🔕 No significant changes detected for Match ID: {$match->id}");
//         }
//     });
// }

protected static function boot()
{
    parent::boot();

    static::updated(function ($match) {
        Log::info("🔄 Match update detected for ID: {$match->id}");

        // Determine match type.
        $matchType = '';
        $columnsToCheck = [];

        if ($match->player1_id && $match->player2_id) {
            // It's a singles match.
            $matchType = 'singles';
            $columnsToCheck = [
                'stage', 'match_date', 'match_time',
                'set1_player1_points', 'set1_player2_points',
                'set2_player1_points', 'set2_player2_points',
                'set3_player1_points', 'set3_player2_points'
            ];
        } elseif (
            $match->team1_player1_id &&
            $match->team1_player2_id &&
            $match->team2_player1_id &&
            $match->team2_player2_id
        ) {
            // It's a doubles match.
            $matchType = 'doubles';
            $columnsToCheck = [
                'stage', 'match_date', 'match_time',
                'set1_team1_points', 'set1_team2_points',
                'set2_team1_points', 'set2_team2_points',
                'set3_team1_points', 'set3_team2_points',
                'team1_player1_id', 'team1_player2_id',
                'team2_player1_id', 'team2_player2_id'
            ];
        } else {
            // Fallback if match type is unclear; you can adjust as needed.
            $matchType = 'unknown';
            $columnsToCheck = ['stage', 'match_date', 'match_time'];
        }

        $changes = [];

        foreach ($columnsToCheck as $column) {
            $oldValue = (string) $match->getOriginal($column);
            $newValue = (string) $match->{$column};

            if ($oldValue !== $newValue) {
                $changes[$column] = [
                    'old' => $oldValue,
                    'new' => $newValue
                ];
            }
        }

        if (!empty($changes)) {
            Log::info("🔔 Significant changes detected for {$matchType} Match ID: {$match->id}", $changes);

            // Retrieve all users who have favorited (pinned) this match.
            $favoritedByUsers = Favorite::where('favoritable_id', $match->id)
                ->where('favoritable_type', Matches::class)
                ->pluck('user_id')
                ->unique();

            collect($favoritedByUsers)->each(function ($userId) use ($match, $changes, $matchType) {
                $user = \App\Models\User::find($userId);

                if ($user) {
                    try {
                        Log::info("📨 Sending email to {$user->email} for {$matchType} Match ID: {$match->id}");
                        Mail::to($user->email)->queue(new MatchUpdatedNotification($user, $match, $changes));
                        Log::info("✅ Email successfully queued to: {$user->email}");
                    } catch (\Exception $e) {
                        Log::error("❌ Email sending failed for {$user->email}: " . $e->getMessage());
                    }
                }
            });
        } else {
            Log::info("🔕 No significant changes detected for Match ID: {$match->id}");
        }
    });
}


public function createdBy()
{
    return $this->belongsTo(User::class, 'created_by');
}


protected static function booted()
{
    static::creating(function ($match) {
        // If there's an authenticated user and created_by is not already set
        if (auth()->check() && empty($match->created_by)) {
            $match->created_by = auth()->id();
        }
    });
}

public function getPlayersAttribute()
{
    // For singles match
    if ($this->player1_id && $this->player2_id) {
        return collect([$this->player1, $this->player2]);
    }
    // For doubles match
    if (
        $this->team1_player1_id &&
        $this->team1_player2_id &&
        $this->team2_player1_id &&
        $this->team2_player2_id
    ) {
        return collect([
            $this->team1Player1,
            $this->team1Player2,
            $this->team2Player1,
            $this->team2Player2
        ]);
    }
    return collect();
}


}
