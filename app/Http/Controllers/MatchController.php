<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Matches;
use App\Models\Tournament;
use App\Models\Category;
use App\Models\Player;

class MatchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Lock/unlock singles tournaments
    public function lockSinglesTournament(Request $request)
    {
        $validated = $request->validate([
            'tournament_id' => 'required|exists:tournaments,id'
        ]);

        session(['locked_singles_tournament_id' => $validated['tournament_id']]);
        return redirect()->back()->with('success', 'Singles tournament locked successfully.');
    }

    public function unlockSinglesTournament()
    {
        session()->forget('locked_singles_tournament_id');
        return redirect()->back()->with('success', 'Singles tournament unlocked successfully.');
    }

    // Lock/unlock doubles tournaments
    public function lockDoublesTournament(Request $request)
    {
        $validated = $request->validate([
            'tournament_id' => 'required|exists:tournaments,id'
        ]);

        session(['locked_doubles_tournament_id' => $validated['tournament_id']]);
        return redirect()->back()->with('success', 'Doubles tournament locked successfully.');
    }

    public function unlockDoublesTournament()
    {
        session()->forget('locked_doubles_tournament_id');
        return redirect()->back()->with('success', 'Doubles tournament unlocked successfully.');
    }

    // Display form to create a Singles Match
    public function createSingles()
{
    $user = Auth::user();

    // Fetch tournaments created by the user or where the user is a moderator
    $tournaments = Tournament::where('created_by', $user->id)
        ->orWhereHas('moderators', fn($q) => $q->where('user_id', $user->id))
        ->get();

    // Fetch locked tournament ID from session
    $lockedTournamentId = session('locked_singles_tournament_id');
    $lockedTournament = $lockedTournamentId ? Tournament::find($lockedTournamentId) : null;

    // Fetch only Singles categories (BS, GS) using LIKE
    $categories = Category::where('name', 'LIKE', '%BS%')
        ->orWhere('name', 'LIKE', '%GS%')
        ->get();

    return view('matches.singles.create', compact('tournaments', 'lockedTournament', 'categories'));
}


    // Fetch players for singles category
    public function filteredPlayersSingles(Request $request)
    {
        \Log::info("🔍 API called: filteredPlayersSingles", ['category_id' => $request->category_id]);
    
        $category = Category::find($request->category_id);
    
        if (!$category) {
            return response()->json([]);
        }
    
        // Extract numeric age values from the 'age_group' string
        preg_match_all('/\d+/', $category->age_group, $matches);
        $ageLimits = $matches[0];
    
        if (count($ageLimits) < 1) {
            \Log::error("❌ Invalid Age Group", ['category' => $category]);
            return response()->json([]);
        }
    
        $minAge = isset($ageLimits[0]) ? (int) $ageLimits[0] : 0;
        $maxAge = isset($ageLimits[1]) ? (int) $ageLimits[1] : 100;
    
        // Fetch players based on extracted age range and valid UID
        $players = Player::where('sex', $category->sex)
                         ->whereBetween('age', [$minAge, $maxAge])
                         ->whereNotNull('uid')
                         ->where('uid', '!=', '')
                         ->select('id', 'uid', 'name', 'age', 'sex')
                         ->get();
    
        \Log::info("✅ Players Retrieved", ['players' => $players]);
    
        return response()->json($players);
    }
    
    

    // Edit Singles Match
    public function editSingles($id)
    {
        $match = Matches::where('match_type', 'singles')->findOrFail($id);
        return view('matches.singles.edit', compact('match'));
    }

    // Update Singles Match
    public function updateSingles(Request $request, $id)
    {
        $validated = $request->validate([
            'stage'         => 'required|string',
            'match_date'    => 'required|date',
            'match_time'    => 'required',
            'set1_player1_points' => 'nullable|integer',
            'set1_player2_points' => 'nullable|integer',
            'set2_player1_points' => 'nullable|integer',
            'set2_player2_points' => 'nullable|integer',
            'set3_player1_points' => 'nullable|integer',
            'set3_player2_points' => 'nullable|integer',
        ]);

        $match = Matches::where('match_type', 'singles')->findOrFail($id);
        $match->update($validated);

        return redirect()->route('matches.singles.index')->with('success', 'Singles match updated successfully.');
    }

    // Delete Singles Match
    public function deleteSingles($id)
    {
        $match = Matches::where('match_type', 'singles')->findOrFail($id);
        $match->delete();

        return redirect()->route('matches.singles.index')->with('success', 'Singles match deleted successfully.');
    }
}
