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

    // ✅ List all singles matches
    public function indexSingles(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user->is_admin;

        $matches = Matches::with(['tournament', 'category', 'player1', 'player2'])
            ->whereNull('deleted_at');

        if (!$isAdmin) {
            $matches->where(function ($q) use ($user) {
                $q->where('created_by', $user->id)
                    ->orWhereHas('tournament.moderators', fn ($q2) => $q2->where('user_id', $user->id));
            });
        }

        $matches = $matches->orderBy('id')->paginate(10);
        $tournaments = Tournament::all();
        $categories = Category::all();
        $players = Player::all();

        return view('matches.singles.index', compact('matches', 'tournaments', 'categories', 'players'));
    }

    // ✅ Lock a tournament
    public function lockTournament(Request $request)
    {
        $request->validate(['tournament_id' => 'required|exists:tournaments,id']);

        $tournament = Tournament::find($request->tournament_id);
        session(['locked_tournament' => $tournament->id, 'locked_tournament_name' => $tournament->name]);

        return redirect()->back()->with('success', 'Tournament locked: ' . $tournament->name);
    }

    // ✅ Unlock a tournament
    public function unlockTournament(Request $request)
    {
        session()->forget(['locked_tournament', 'locked_tournament_name']);
        return redirect()->back()->with('success', 'Tournament unlocked');
    }

    // ✅ Show the create form
    public function createSingles(Request $request)
    {
        $user = Auth::user();
        $tournaments = Tournament::where('created_by', $user->id)->orWhereHas('moderators', fn ($q) => $q->where('user_id', $user->id))->get();
        $lockedTournamentId = session('locked_tournament');
        $lockedTournament = $lockedTournamentId ? Tournament::find($lockedTournamentId) : null;
        $players = Player::all();
        $categories = Category::whereHas('tournaments', fn ($q) => $q->where('tournament_id', $lockedTournamentId))->get();

        return view('matches.singles.create', compact('tournaments', 'lockedTournament', 'players', 'categories'));
    }

    // ✅ Store a new singles match
    public function storeSingles(Request $request)
    {
        if (!session('locked_tournament')) {
            return redirect()->back()->withErrors('You must lock a tournament before adding a match.');
        }

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'player1_id' => 'required|exists:players,id|different:player2_id',
            'player2_id' => 'required|exists:players,id',
            'stage' => 'required|string',
            'date' => 'required|date',
            'match_time' => 'required',
            'set1_player1_points' => 'required|integer',
            'set1_player2_points' => 'required|integer',
            'set2_player1_points' => 'required|integer',
            'set2_player2_points' => 'required|integer',
            'set3_player1_points' => 'nullable|integer',
            'set3_player2_points' => 'nullable|integer',
        ]);

        Matches::create($request->all() + ['tournament_id' => session('locked_tournament'), 'created_by' => Auth::id()]);

        return redirect()->route('matches.singles.index')->with('success', 'Match successfully added!');
    }

    public function editSingles($id)
{
    $match = Matches::with(['tournament', 'category', 'player1', 'player2'])->findOrFail($id);

    // Check permissions
    $user = Auth::user();
    $isAdmin = $user->is_admin;

    if (!$isAdmin && $match->created_by != $user->id) {
        if (!$match->tournament || !$match->tournament->moderators()->where('user_id', $user->id)->exists()) {
            abort(403, 'You do not have permission to edit this match.');
        }
    }

    // Match stages
    $stages = ['Pre Quarter Finals', 'Quarter Finals', 'Semifinals', 'Finals'];

    return view('matches.singles.edit', compact('match', 'stages'));
}
public function editSinglesTable()
{
    $matches = Matches::with(['tournament', 'category', 'player1', 'player2'])->paginate(10);
    return view('matches.singles.edit', compact('matches'));
}

public function updateSingles(Request $request, $id)
{
    $match = Matches::findOrFail($id);
    
    $request->validate([
        'match_date'            => 'required|date',
        'match_time'            => 'required',
        'stage'                 => 'required|string',
        'set1_player1_points'   => 'nullable|integer',
        'set1_player2_points'   => 'nullable|integer',
        'set2_player1_points'   => 'nullable|integer',
        'set2_player2_points'   => 'nullable|integer',
        'set3_player1_points'   => 'nullable|integer',
        'set3_player2_points'   => 'nullable|integer',
    ]);

    $match->update($request->all());

    return redirect()->route('matches.singles.edit')->with('success', 'Match updated successfully.');
}

public function deleteSingles($id)
{
    $match = Matches::findOrFail($id);
    $match->delete();
    return redirect()->route('matches.singles.edit')->with('success', 'Match deleted successfully.');
}


}
