<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function toggle(Request $request)
    {
        \Log::info('🔍 Favorite toggle request:', $request->all()); // ✅ Log received data
    
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        try {
            $validated = $request->validate([
                'favoritable_id' => 'required|integer|exists:matches,id',
                'favoritable_type' => 'required|in:App\\Models\\Tournament,App\\Models\\Matches,App\\Models\\Category,App\\Models\\Player'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('❌ Validation Error:', $e->errors()); // 🚀 Log validation errors
            return response()->json(['errors' => $e->errors()], 422);
        }
    
        \Log::info('✅ Validation Passed:', $validated); // ✅ Log successful validation
    
        $user = Auth::user();
    
        $existingFavorite = Favorite::where([
            'user_id' => $user->id,
            'favoritable_id' => $validated['favoritable_id'],
            'favoritable_type' => $validated['favoritable_type']
        ])->first();
    
        if ($existingFavorite) {
            $existingFavorite->delete();
            return response()->json(['status' => 'unpinned']);
        } else {
            Favorite::create([
                'user_id' => $user->id,
                'favoritable_id' => $validated['favoritable_id'],
                'favoritable_type' => $validated['favoritable_type']
            ]);
            return response()->json(['status' => 'pinned']);
        }
    }
    


    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to view favorites.');
        }
    
        $favorites = Auth::user()->favorites()->get();
        return view('dashboard.favorites', compact('favorites'));
    }
    
}
