<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Matches;
use App\Models\Player;
use App\Models\Tournament;
use App\Mail\MatchPinnedNotification;
use App\Mail\PlayerPinnedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class FavoriteController extends Controller
{
    public function toggle(Request $request)
    {
        \Log::info('🔍 Favorite toggle request:', $request->all());

        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'favoritable_id'   => 'required|integer',
            'favoritable_type' => 'required|in:App\\Models\\Tournament,App\\Models\\Matches,App\\Models\\Category,App\\Models\\Player'
        ]);

        \Log::info('✅ Validation Passed:', $validated);

        $user = Auth::user();

        $existingFavorite = Favorite::where([
            'user_id'           => $user->id,
            'favoritable_id'    => $validated['favoritable_id'],
            'favoritable_type'  => $validated['favoritable_type']
        ])->first();

        if ($existingFavorite) {
            $existingFavorite->delete();
            $action = "unpinned";
        } else {
            Favorite::create([
                'user_id'           => $user->id,
                'favoritable_id'    => $validated['favoritable_id'],
                'favoritable_type'  => $validated['favoritable_type']
            ]);
            $action = "pinned";
        }

        // Send email notification for matches and players
        try {
            if ($validated['favoritable_type'] === "App\Models\Matches") {
                $match = Matches::find($validated['favoritable_id']);
                if ($match) {
                    Mail::to($user->email)->queue(new MatchPinnedNotification($user, $match, $action));
                }
            } elseif ($validated['favoritable_type'] === "App\Models\Player") {
                $player = Player::find($validated['favoritable_id']);
                if ($player) {
                    Mail::to($user->email)->queue(new PlayerPinnedNotification($user, $player, $action));
                }
            }
            \Log::info("📩 Email queued for {$validated['favoritable_type']} {$action} to {$user->email}");
        } catch (\Exception $e) {
            \Log::error("❌ Failed to send email notification: " . $e->getMessage());
        }

        return response()->json(['status' => $action]);
    }

    public function index()
{
    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'You must be logged in to view favorites.');
    }

    $favorites = Auth::user()->favorites()
        ->orderByDesc('favoritable_id') // Sort by favoritable_id instead of the favorite table's id
        ->get();

    return view('dashboard.favorites', compact('favorites'));
}

}