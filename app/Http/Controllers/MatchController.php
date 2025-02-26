<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Matches as MatchModel;
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

        // Fetch only Singles categories
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

        $categoryName = strtoupper($category->name);
        $sex = $category->sex;
        $minAge = 0;
        $maxAge = 100;

        if (preg_match('/^U(\d+)(BS|GS)$/', $categoryName, $matches)) {
            $maxAge = (int) $matches[1];
        } elseif (preg_match('/^SENIOR (\d+) PLUS (BS|GS)$/', $categoryName, $matches)) {
            $minAge = (int) $matches[1];
        } elseif (stripos($categoryName, 'OPEN') !== false) {
        } else {
            \Log::error("❌ Unrecognized Category Format", ['category' => $category]);
            return response()->json([]);
        }

        // Fetch players
        $players = Player::where('sex', $sex)
                         ->whereBetween('age', [$minAge, $maxAge])
                         ->whereNotNull('uid')
                         ->where('uid', '!=', '')
                         ->select('id', 'uid', 'name', 'age', 'sex')
                         ->get();

        \Log::info("✅ Players Retrieved", ['players' => $players]);

        return response()->json($players);
    }

    // Store Singles Match
    public function storeSingles(Request $request)
    {
        $validated = $request->validate([
            'tournament_id' => 'required|exists:tournaments,id',
            'category_id' => 'required|exists:categories,id',
            'match_date' => 'required|date',
            'match_time' => 'required',
            'player1_id' => 'required|exists:players,id',
            'player2_id' => 'required|exists:players,id|different:player1_id',
            'stage' => 'required|string',
            'set1_player1_points' => 'nullable|integer',
            'set1_player2_points' => 'nullable|integer',
            'set2_player1_points' => 'nullable|integer',
            'set2_player2_points' => 'nullable|integer',
            'set3_player1_points' => 'nullable|integer',
            'set3_player2_points' => 'nullable|integer',
        ]);
    
        // Insert match data into database
        MatchModel::create([
            'tournament_id' => $validated['tournament_id'],
            'category_id' => $validated['category_id'],
            'match_date' => $validated['match_date'],
            'match_time' => $validated['match_time'],
            'player1_id' => $validated['player1_id'],
            'player2_id' => $validated['player2_id'],
            'stage' => $validated['stage'],
            'set1_player1_points' => $validated['set1_player1_points'] ?? 0,
            'set1_player2_points' => $validated['set1_player2_points'] ?? 0,
            'set2_player1_points' => $validated['set2_player1_points'] ?? 0,
            'set2_player2_points' => $validated['set2_player2_points'] ?? 0,
            'set3_player1_points' => $validated['set3_player1_points'] ?? 0,
            'set3_player2_points' => $validated['set3_player2_points'] ?? 0,
            'created_by' => Auth::id(),
        ]);
    
        return redirect()->route('matches.singles.index')->with('success', 'Singles match created successfully.');
    }
    

    // Display all Singles Matches
    public function indexSingles()
    {
        $matches = MatchModel::singles()
                             ->with(['tournament', 'category', 'player1', 'player2'])
                             ->get();

        return view('matches.singles.index', compact('matches'));
    }

    // Display all Doubles Matches
    public function indexDoubles()
    {
        $matches = MatchModel::doubles()
                             ->with(['tournament', 'category', 'team1Player1', 'team1Player2', 'team2Player1', 'team2Player2'])
                             ->get();

        return view('matches.doubles.index', compact('matches'));
    }
}
