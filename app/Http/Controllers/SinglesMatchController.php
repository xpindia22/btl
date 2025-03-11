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
        \Log::info("🔍 API Request - filteredPlayers()", ['category_id' => $request->category_id]);

        if (!$request->has('category_id')) {
            \Log::error("❌ Missing category_id in request!");
            return response()->json(['error' => 'Category ID is required'], 400);
        }

        $category = Category::find($request->category_id);
        if (!$category) {
            \Log::error("❌ No category found for ID: " . $request->category_id);
            return response()->json([]);
        }

        $categoryName = strtoupper($category->name);
        $sex = $category->sex;
        $minAge = 0;
        $maxAge = 100;

        \Log::info("✅ Category Selected: {$categoryName} | Sex: {$sex}");

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

        \Log::info("✅ Players Found: " . $players->count(), $players->toArray());

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

        return redirect()->route('matches.singles.index')->with('success', 'Singles match created successfully.');
    }

    // Display Singles Matches
    public function index(Request $request)
{
    $tournaments = Tournament::all();
    $players = Player::all();

    $query = MatchModel::with(['tournament', 'category', 'player1', 'player2'])
                ->whereHas('category', function ($q) {
                    $q->where('name', 'LIKE', '%BS%')
                      ->orWhere('name', 'LIKE', '%GS%');
                });

    if ($request->filled('filter_tournament') && $request->filter_tournament !== 'all') {
        $query->where('tournament_id', $request->filter_tournament);
    }

    if ($request->filled('filter_player1') && $request->filter_player1 !== 'all') {
        $query->where('player1_id', $request->filter_player1);
    }

    if ($request->filled('filter_player2') && $request->filter_player2 !== 'all') {
        $query->where('player2_id', $request->filter_player2);
    }

    if ($request->filled('filter_category') && $request->filter_category !== 'all') {
        $query->whereHas('category', function ($q) use ($request) {
            $q->where('name', 'LIKE', $request->filter_category);
        });
    }

    if ($request->filled('filter_date')) {
        $query->whereDate('match_date', $request->filter_date);
    }

    if ($request->filled('filter_stage') && $request->filter_stage !== 'all') {
        $query->where('stage', $request->filter_stage);
    }

    // Order by creation date descending so that newest match appears first.
    $matches = $query->orderBy('created_at', 'desc')->paginate(10);

    return view('matches.singles.index', compact('tournaments', 'players', 'matches'));
}


    // Update Match
    public function update(Request $request, $id)
{
    $validated = $request->validate([
        'stage' => 'required|string',
        'match_date' => 'required|date',
        'match_time' => 'required',
        'set1_player1_points' => 'nullable|integer',
        'set1_player2_points' => 'nullable|integer',
        'set2_player1_points' => 'nullable|integer',
        'set2_player2_points' => 'nullable|integer',
        'set3_player1_points' => 'nullable|integer',
        'set3_player2_points' => 'nullable|integer',
    ]);

    $match = MatchModel::findOrFail($id);
    $match->update($validated);

    return response()->json(['message' => 'Singles match updated successfully.']);
}

    // Delete Match
    public function delete($id)
    {
        $match = MatchModel::findOrFail($id);
        $match->delete();

        return response()->json(['message' => 'Match deleted successfully.']);
    }

    // Display Singles Matches for Inline Editing
public function edit()
{
    $matches = MatchModel::with(['tournament', 'category', 'player1', 'player2'])
    ->orderBy('created_at', 'desc')

        ->paginate(10);
    
    return view('matches.singles.edit', compact('matches'));
    
}


public function show($id)
{
    $match = \App\Models\Matches::find($id);

    if (!$match) {
        abort(404, 'Match not found');
    }

    return view('matches.singles.show', compact('match'));
}

}
