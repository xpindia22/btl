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

    // Lock/unlock Singles tournaments
    public function lockSinglesTournament(Request $request)
    {
        $validated = $request->validate(['tournament_id' => 'required|exists:tournaments,id']);
        session(['locked_singles_tournament_id' => $validated['tournament_id']]);
        return redirect()->back()->with('success', 'Singles tournament locked successfully.');
    }

    public function unlockSinglesTournament()
    {
        session()->forget('locked_singles_tournament_id');
        return redirect()->back()->with('success', 'Singles tournament unlocked successfully.');
    }

    // Lock/unlock Doubles tournaments
    public function lockDoublesTournament(Request $request)
    {
        $validated = $request->validate(['tournament_id' => 'required|exists:tournaments,id']);
        session(['locked_doubles_tournament_id' => $validated['tournament_id']]);
        return redirect()->back()->with('success', 'Doubles tournament locked successfully.');
    }

    public function unlockDoublesTournament()
    {
        session()->forget('locked_doubles_tournament_id');
        return redirect()->back()->with('success', 'Doubles tournament unlocked successfully.');
    }

    // Create Singles Match View
    public function createSingles()
    {
        $user = Auth::user();
        $tournaments = Tournament::where('created_by', $user->id)
            ->orWhereHas('moderators', fn($q) => $q->where('user_id', $user->id))
            ->get();

        $lockedTournamentId = session('locked_singles_tournament_id');
        $lockedTournament = $lockedTournamentId ? Tournament::find($lockedTournamentId) : null;

        $categories = Category::where('name', 'LIKE', '%BS%')
            ->orWhere('name', 'LIKE', '%GS%')
            ->get();

        return view('matches.singles.create', compact('tournaments', 'lockedTournament', 'categories'));
    }

    // Create Doubles Match View
    public function createDoubles()
    {
        $user = Auth::user();
        $tournaments = Tournament::where('created_by', $user->id)
            ->orWhereHas('moderators', fn($q) => $q->where('user_id', $user->id))
            ->get();

        $lockedTournamentId = session('locked_doubles_tournament_id');
        $lockedTournament = $lockedTournamentId ? Tournament::find($lockedTournamentId) : null;

        $categories = Category::where('name', 'LIKE', '%BD%')
            ->orWhere('name', 'LIKE', '%GD%')
            ->orWhere('name', 'LIKE', '%XD%')
            ->get();

        return view('matches.doubles.create', compact('tournaments', 'lockedTournament', 'categories'));
    }

    // Fetch players for Singles Matches
    public function filteredPlayersSingles(Request $request)
    {
        \Log::info("🔍 API called: filteredPlayersSingles", ['category_id' => $request->category_id]);

        $category = Category::find($request->category_id);
        if (!$category) return response()->json([]);

        $categoryName = strtoupper($category->name);
        $sex = $category->sex;
        $minAge = 0;
        $maxAge = 100;

        if (preg_match('/^U(\d+)(BS|GS)$/', $categoryName, $matches)) {
            $maxAge = (int) $matches[1];
        } elseif (preg_match('/^SENIOR (\d+) PLUS (BS|GS)$/', $categoryName, $matches)) {
            $minAge = (int) $matches[1];
        }

        $players = Player::where('sex', $sex)
                         ->whereBetween('age', [$minAge, $maxAge])
                         ->whereNotNull('uid')
                         ->where('uid', '!=', '')
                         ->select('id', 'name', 'age', 'sex')
                         ->get();

        return response()->json($players);
    }

    // Fetch players for Doubles Matches
    public function filteredPlayersDoubles(Request $request)
    {
        \Log::info("🔍 API called: filteredPlayersDoubles", ['category_id' => $request->category_id]);

        $category = Category::find($request->category_id);
        if (!$category) return response()->json([]);

        $categoryName = strtoupper($category->name);
        $minAge = 0;
        $maxAge = 100;
        $sexFilter = [];

        if (preg_match('/^U(\d+)(BD|GD|XD)$/', $categoryName, $matches)) {
            $maxAge = (int) $matches[1];
        } elseif (preg_match('/^SENIOR (\d+) PLUS (BD|GD|XD)$/', $categoryName, $matches)) {
            $minAge = (int) $matches[1];
        }

        if (strpos($categoryName, 'BD') !== false) {
            $sexFilter = ['M'];
        } elseif (strpos($categoryName, 'GD') !== false) {
            $sexFilter = ['F'];
        } elseif (strpos($categoryName, 'XD') !== false) {
            $sexFilter = ['M', 'F'];
        }

        $players = Player::whereBetween('age', [$minAge, $maxAge])
                         ->whereIn('sex', $sexFilter)
                         ->whereNotNull('uid')
                         ->where('uid', '!=', '')
                         ->select('id', 'name', 'age', 'sex')
                         ->get();

        return response()->json($players);
    }

    // Store Doubles Match
    public function storeDoubles(Request $request)
    {
        $validated = $request->validate([
            'tournament_id' => 'required|exists:tournaments,id',
            'category_id' => 'required|exists:categories,id',
            'match_date' => 'required|date',
            'match_time' => 'required',
            'stage' => 'required|string',
            'team1_player1_id' => 'required|exists:players,id',
            'team1_player2_id' => 'required|exists:players,id|different:team1_player1_id',
            'team2_player1_id' => 'required|exists:players,id',
            'team2_player2_id' => 'required|exists:players,id|different:team2_player1_id',
        ]);

        MatchModel::create([
            'tournament_id' => $validated['tournament_id'],
            'category_id' => $validated['category_id'],
            'match_date' => $validated['match_date'],
            'match_time' => $validated['match_time'],
            'stage' => $validated['stage'],
            'team1_player1_id' => $validated['team1_player1_id'],
            'team1_player2_id' => $validated['team1_player2_id'],
            'team2_player1_id' => $validated['team2_player1_id'],
            'team2_player2_id' => $validated['team2_player2_id'],
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('matches.doubles.index')->with('success', 'Doubles match created successfully.');
    }

    // Display all Singles Matches
    public function indexSingles()
    {
        $matches = MatchModel::singles()->with(['tournament', 'category', 'player1', 'player2'])->get();
        return view('matches.singles.index', compact('matches'));
    }

    // Display all Doubles Matches
    public function indexDoubles()
    {
        $matches = MatchModel::doubles()->with(['tournament', 'category', 'team1Player1', 'team1Player2', 'team2Player1', 'team2Player2'])->get();
        return view('matches.doubles.index', compact('matches'));
    }


       // ======================= EDIT & UPDATE MATCHES ========================= //

    // Edit Singles Match
    public function editSingles($id)
    {
        $match = MatchModel::singles()->findOrFail($id);
        $tournaments = Tournament::all();
        $categories = Category::where('name', 'LIKE', '%BS%')->orWhere('name', 'LIKE', '%GS%')->get();
        $players = Player::all();

        return view('matches.singles.edit', compact('match', 'tournaments', 'categories', 'players'));
    }

    // Update Singles Match
    public function updateSingles(Request $request, $id)
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

        $match = MatchModel::singles()->findOrFail($id);
        $match->update($validated);

        return redirect()->route('matches.singles.index')->with('success', 'Singles match updated successfully.');
    }

    // Edit Doubles Match
    public function editDoubles($id)
    {
        $match = MatchModel::doubles()->findOrFail($id);
        $tournaments = Tournament::all();
        $categories = Category::where('name', 'LIKE', '%BD%')
                              ->orWhere('name', 'LIKE', '%GD%')
                              ->orWhere('name', 'LIKE', '%XD%')
                              ->get();
        $players = Player::all();

        return view('matches.doubles.edit', compact('match', 'tournaments', 'categories', 'players'));
    }

    // Update Doubles Match
    public function updateDoubles(Request $request, $id)
    {
        $validated = $request->validate([
            'tournament_id' => 'required|exists:tournaments,id',
            'category_id' => 'required|exists:categories,id',
            'match_date' => 'required|date',
            'match_time' => 'required',
            'stage' => 'required|string',
            'team1_player1_id' => 'required|exists:players,id',
            'team1_player2_id' => 'required|exists:players,id|different:team1_player1_id',
            'team2_player1_id' => 'required|exists:players,id',
            'team2_player2_id' => 'required|exists:players,id|different:team2_player1_id',
            'set1_team1_points' => 'nullable|integer',
            'set1_team2_points' => 'nullable|integer',
            'set2_team1_points' => 'nullable|integer',
            'set2_team2_points' => 'nullable|integer',
            'set3_team1_points' => 'nullable|integer',
            'set3_team2_points' => 'nullable|integer',
        ]);

        $match = MatchModel::doubles()->findOrFail($id);
        $match->update($validated);

        return redirect()->route('matches.doubles.index')->with('success', 'Doubles match updated successfully.');
    }
}
