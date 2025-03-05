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
    // public function lockDoublesTournament(Request $request)
    // {
    //     $validated = $request->validate(['tournament_id' => 'required|exists:tournaments,id']);
    //     session(['locked_doubles_tournament_id' => $validated['tournament_id']]);
    //     return redirect()->back()->with('success', 'Doubles tournament locked successfully.');
    // }

    // public function unlockDoublesTournament()
    // {
    //     session()->forget('locked_doubles_tournament_id');
    //     return redirect()->back()->with('success', 'Doubles tournament unlocked successfully.');
    // }

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
    // public function createDoubles()
    // {
    //     $user = Auth::user();
    //     $tournaments = Tournament::where('created_by', $user->id)
    //         ->orWhereHas('moderators', fn($q) => $q->where('user_id', $user->id))
    //         ->get();

    //     $lockedTournamentId = session('locked_doubles_tournament_id');
    //     $lockedTournament = $lockedTournamentId ? Tournament::find($lockedTournamentId) : null;

    //     $categories = Category::where('name', 'LIKE', '%BD%')
    //         ->orWhere('name', 'LIKE', '%GD%')
    //         ->orWhere('name', 'LIKE', '%XD%')
    //         ->get();

    //     return view('matches.doubles.create', compact('tournaments', 'lockedTournament', 'categories'));
    // }

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
    // public function filteredPlayersDoubles(Request $request)
    // {
    //     \Log::info("🔍 API called: filteredPlayersDoubles", ['category_id' => $request->category_id]);

    //     $category = Category::find($request->category_id);
    //     if (!$category) return response()->json([]);

    //     $categoryName = strtoupper($category->name);
    //     $minAge = 0;
    //     $maxAge = 100;
    //     $sexFilter = [];

    //     if (preg_match('/^U(\d+)(BD|GD|XD)$/', $categoryName, $matches)) {
    //         $maxAge = (int) $matches[1];
    //     } elseif (preg_match('/^SENIOR (\d+) PLUS (BD|GD|XD)$/', $categoryName, $matches)) {
    //         $minAge = (int) $matches[1];
    //     }

    //     if (strpos($categoryName, 'BD') !== false) {
    //         $sexFilter = ['M'];
    //     } elseif (strpos($categoryName, 'GD') !== false) {
    //         $sexFilter = ['F'];
    //     } elseif (strpos($categoryName, 'XD') !== false) {
    //         $sexFilter = ['M', 'F'];
    //     }

    //     $players = Player::whereBetween('age', [$minAge, $maxAge])
    //                      ->whereIn('sex', $sexFilter)
    //                      ->whereNotNull('uid')
    //                      ->where('uid', '!=', '')
    //                      ->select('id', 'name', 'age', 'sex')
    //                      ->get();

    //     return response()->json($players);
    // }

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

        // Create the match
        MatchModel::create([
            'tournament_id' => $validated['tournament_id'],
            'category_id' => $validated['category_id'],
            'match_date' => $validated['match_date'],
            'match_time' => $validated['match_time'],
            'player1_id' => $validated['player1_id'],
            'player2_id' => $validated['player2_id'],
            'stage' => $validated['stage'],
            'set1_player1_points' => $validated['set1_player1_points'] ?? null,
            'set1_player2_points' => $validated['set1_player2_points'] ?? null,
            'set2_player1_points' => $validated['set2_player1_points'] ?? null,
            'set2_player2_points' => $validated['set2_player2_points'] ?? null,
            'set3_player1_points' => $validated['set3_player1_points'] ?? null,
            'set3_player2_points' => $validated['set3_player2_points'] ?? null,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('matches.singles.index')->with('success', 'Singles match created successfully.');
    }

    // Store Doubles Match
    // public function storeDoubles(Request $request)
    // {
    //     $validated = $request->validate([
    //         'tournament_id' => 'required|exists:tournaments,id',
    //         'category_id' => 'required|exists:categories,id',
    //         'match_date' => 'required|date',
    //         'match_time' => 'required',
    //         'stage' => 'required|string',
    //         'team1_player1_id' => 'required|exists:players,id',
    //         'team1_player2_id' => 'required|exists:players,id|different:team1_player1_id',
    //         'team2_player1_id' => 'required|exists:players,id',
    //         'team2_player2_id' => 'required|exists:players,id|different:team2_player1_id',
    //     ]);

    //     MatchModel::create([
    //         'tournament_id' => $validated['tournament_id'],
    //         'category_id' => $validated['category_id'],
    //         'match_date' => $validated['match_date'],
    //         'match_time' => $validated['match_time'],
    //         'stage' => $validated['stage'],
    //         'team1_player1_id' => $validated['team1_player1_id'],
    //         'team1_player2_id' => $validated['team1_player2_id'],
    //         'team2_player1_id' => $validated['team2_player1_id'],
    //         'team2_player2_id' => $validated['team2_player2_id'],
    //         'created_by' => Auth::id(),
    //     ]);

    //     return redirect()->route('matches.doubles.index')->with('success', 'Doubles match created successfully.');
    // }

    // Display all Singles Matches (Paginated)
    // Display all Singles Matches (Paginated with filters)
    public function indexSingles(Request $request)
    {
        $tournaments = Tournament::all();
        $players = Player::all(); // Fetch players list
    
        $query = MatchModel::singles()
            ->with(['tournament', 'category', 'player1', 'player2']);
    
        $query->whereHas('category', function($q) {
            $q->where('name', 'LIKE', '%BS%')
              ->orWhere('name', 'LIKE', '%GS%');
        });
    
        // Apply filters
        if ($request->has('filter_tournament') && $request->filter_tournament != 'all') {
            $query->where('tournament_id', $request->filter_tournament);
        }
        if ($request->has('filter_category') && $request->filter_category != 'all') {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('name', 'LIKE', "%{$request->filter_category}%");
            });
        }
        if ($request->has('filter_player1') && $request->filter_player1 != 'all') {
            $query->whereHas('player1', function($q) use ($request) {
                $q->where('id', $request->filter_player1);
            });
        }
        if ($request->has('filter_player2') && $request->filter_player2 != 'all') {
            $query->whereHas('player2', function($q) use ($request) {
                $q->where('id', $request->filter_player2);
            });
        }
        if ($request->has('filter_stage') && $request->filter_stage != 'all') {
            $query->where('stage', $request->filter_stage);
        }
        if ($request->has('filter_match_date') && $request->filter_match_date) {
            $query->where('match_date', $request->filter_match_date);
        }
    
        $matches = $query->paginate(10);
    
        return view('matches.singles.index', compact('matches', 'tournaments', 'players'));
    }
    
    // Display all Doubles Matches (Paginated with filters)
    // public function indexDoubles(Request $request)
    // {
    //     // Fetch tournaments (for the filter dropdown)
    //     $tournaments = Tournament::all();

    //     // Base query: only doubles, plus relationships for teams
    //     $query = MatchModel::doubles()
    //         ->with([
    //             'tournament', 'category',
    //             'team1Player1', 'team1Player2',
    //             'team2Player1', 'team2Player2'
    //         ]);

    //     // Ensure categories contain BD, GD, or XD 
    //     $query->whereHas('category', function($q) {
    //         $q->where('name', 'LIKE', '%BD%')
    //           ->orWhere('name', 'LIKE', '%GD%')
    //           ->orWhere('name', 'LIKE', '%XD%');
    //     });

    //     // OPTIONAL FILTERS
    //     if ($request->has('filter_tournament') && $request->filter_tournament != 'all') {
    //         $query->where('tournament_id', $request->filter_tournament);
    //     }

    //     if ($request->has('filter_category') && $request->filter_category != 'all') {
    //         $filterCat = $request->filter_category;
    //         $query->whereHas('category', function($q) use ($filterCat) {
    //             $q->where('name','LIKE',"%{$filterCat}%");
    //         });
    //     }

    //     if ($request->has('filter_team1') && $request->filter_team1) {
    //         $team1Name = $request->filter_team1;
    //         $query->where(function($q) use ($team1Name) {
    //             $q->whereHas('team1Player1', function($subQ) use ($team1Name){
    //                 $subQ->where('name','LIKE',"%{$team1Name}%");
    //             })
    //             ->orWhereHas('team1Player2', function($subQ) use ($team1Name){
    //                 $subQ->where('name','LIKE',"%{$team1Name}%");
    //             });
    //         });
    //     }

    //     if ($request->has('filter_team2') && $request->filter_team2) {
    //         $team2Name = $request->filter_team2;
    //         $query->where(function($q) use ($team2Name) {
    //             $q->whereHas('team2Player1', function($subQ) use ($team2Name){
    //                 $subQ->where('name','LIKE',"%{$team2Name}%");
    //             })
    //             ->orWhereHas('team2Player2', function($subQ) use ($team2Name){
    //                 $subQ->where('name','LIKE',"%{$team2Name}%");
    //             });
    //         });
    //     }

    //     if ($request->has('filter_stage') && $request->filter_stage != 'all') {
    //         $query->where('stage', $request->filter_stage);
    //     }

    //     if ($request->has('filter_match_date') && $request->filter_match_date) {
    //         $query->where('match_date', $request->filter_match_date);
    //     }

    //     if ($request->has('filter_winner') && $request->filter_winner) {
    //         $winnerFilter = $request->filter_winner;
    //         // Additional filtering logic if needed
    //     }

    //     $matches = $query->orderBy('match_date', 'desc')->paginate(10);

    //     return view('matches.doubles.index', compact('matches','tournaments'));
    // }

    // ======================= EDIT & UPDATE MATCHES ========================= //

    // Edit Singles Match (Paginated)
    public function editSingles()
    {
        $matches = MatchModel::singles()
            ->with(['tournament', 'category', 'player1', 'player2'])
            ->paginate(10);
        return view('matches.singles.edit', compact('matches'));
    }

    // Update Singles Match
    // Update Singles Match
// Update Singles Match
public function updateSingles(Request $request, \App\Models\Matches $match)
{
    try {
        $validated = $request->validate([
            'stage' => 'nullable|string',
            'match_date' => 'nullable|date',
            'match_time' => 'nullable',
            'set1_player1_points' => 'nullable|integer',
            'set1_player2_points' => 'nullable|integer',
            'set2_player1_points' => 'nullable|integer',
            'set2_player2_points' => 'nullable|integer',
            'set3_player1_points' => 'nullable|integer',
            'set3_player2_points' => 'nullable|integer',
        ]);

        $match->update($validated);

        return response()->json(['message' => 'Match updated successfully.']);
    } catch (\Exception $e) {
        \Log::error("Update failed for match {$match->id}: " . $e->getMessage());
        return response()->json(['message' => "Error: " . $e->getMessage()], 500);
    }
}


// Delete Singles Match
public function deleteSingles(\App\Models\Matches $match)
{
    $match->delete();

    return response()->json(['message' => 'Match deleted successfully.']);
}

    
}
