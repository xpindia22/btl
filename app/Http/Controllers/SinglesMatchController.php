<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Matches as MatchModel;
use App\Models\Tournament;
use App\Models\Category;
use App\Models\Player;

class SinglesMatchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Lock/unlock Singles tournaments
    public function lockTournament(Request $request)
    {
        $validated = $request->validate(['tournament_id' => 'required|exists:tournaments,id']);
        session(['locked_singles_tournament_id' => $validated['tournament_id']]);
        return redirect()->back()->with('success', 'Singles tournament locked successfully.');
    }

    public function unlockTournament()
    {
        session()->forget('locked_singles_tournament_id');
        return redirect()->back()->with('success', 'Singles tournament unlocked successfully.');
    }

    // Create Singles Match View
    public function create()
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

    // Fetch players for Singles Matches
    public function filteredPlayers(Request $request)
    {
        \Log::info("🔍 API called: filteredPlayers", ['category_id' => $request->category_id]);

        $category = Category::find($request->category_id);
        if (!$category) return response()->json([]);

        $players = Player::where('sex', $category->sex)
                         ->whereBetween('age', [0, 100])
                         ->whereNotNull('uid')
                         ->where('uid', '!=', '')
                         ->select('id', 'name', 'age', 'sex')
                         ->get();

        return response()->json($players);
    }

    // Store Singles Match
    public function store(Request $request)
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

        MatchModel::create($validated + ['created_by' => Auth::id()]);

        return response()->json(['message' => 'Singles match created successfully.'], 200);
    }

    // ==================== Display Singles Matches for Inline Editing ==================== //
    public function edit()
    {
        $matches = MatchModel::singles()
            ->with(['tournament', 'category', 'player1', 'player2'])
            ->paginate(10);
            
        return view('matches.singles.edit', compact('matches'));
    }

    // ==================== Update Singles Matches (Inline Editing) ==================== //
    public function update(Request $request, $id)
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

            $match = MatchModel::findOrFail($id);
            $match->update($validated);

            return response()->json(['message' => 'Match updated successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => "Error: " . $e->getMessage()], 500);
        }
    }

    // ==================== Delete Singles Matches ==================== //
    public function delete($id)
    {
        try {
            $match = MatchModel::findOrFail($id);
            $match->delete();

            return response()->json(['message' => 'Match deleted successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => "Error: " . $e->getMessage()], 500);
        }
    }
    public function index()
    {
        $tournaments = Tournament::all();
        $players = Player::all(); // Retrieve all players from the database
    
        return view('matches.singles.index', compact('tournaments', 'players', 'matches'));
    }
}
