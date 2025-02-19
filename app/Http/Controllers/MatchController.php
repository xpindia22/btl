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

    // ------------------------------------------------------------------
    // 1) View-Only: indexSingles
    // ------------------------------------------------------------------
    public function indexSingles(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user->is_admin;

        $matchesQuery = Matches::with(['tournament', 'category', 'player1', 'player2'])
            ->whereNull('deleted_at'); // If using soft deletes

        // Limit if not admin
        if (!$isAdmin) {
            $matchesQuery->where(function ($q) use ($user) {
                $q->where('created_by', $user->id)
                  ->orWhereHas('tournament.moderators', fn($q2) => $q2->where('user_id', $user->id));
            });
        }

        $matches = $matchesQuery->orderBy('id')->paginate(10);

        return view('matches.singles.index', compact('matches'));
    }

    // ------------------------------------------------------------------
    // 2) Edit Table: indexSinglesWithEdit
    // ------------------------------------------------------------------
    public function indexSinglesWithEdit()
    {
        $user = Auth::user();
        $isAdmin = $user->is_admin;

        $matchesQuery = Matches::with(['tournament', 'category', 'player1', 'player2'])
            ->whereNull('deleted_at');

        if (!$isAdmin) {
            $matchesQuery->where(function ($q) use ($user) {
                $q->where('created_by', $user->id)
                  ->orWhereHas('tournament.moderators', fn($q2) => $q2->where('user_id', $user->id));
            });
        }

        $matches = $matchesQuery->orderBy('id')->paginate(10);

        return view('matches.singles.edit', compact('matches'));
    }

    // ------------------------------------------------------------------
    // 3) Create & Store
    // ------------------------------------------------------------------
    public function createSingles(Request $request)
    {
        $user = Auth::user();

        // Tournaments
        $tournaments = Tournament::where('created_by', $user->id)
            ->orWhereHas('moderators', fn($q) => $q->where('user_id', $user->id))
            ->get();

        $lockedTournamentId = session('locked_tournament');
        $lockedTournament   = $lockedTournamentId ? Tournament::find($lockedTournamentId) : null;

        $players = Player::all();

        $categories = [];
        if ($lockedTournamentId) {
            $categories = Category::whereHas('tournaments', fn($q) => $q->where('tournament_id', $lockedTournamentId))->get();
        }

        return view('matches.singles.create', compact('tournaments', 'lockedTournament', 'players', 'categories'));
    }

    public function storeSingles(Request $request)
    {
        if (!session('locked_tournament')) {
            return redirect()->back()->withErrors('You must lock a tournament before adding a match.');
        }

        $request->validate([
            'category_id'            => 'required|exists:categories,id',
            'player1_id'             => 'required|exists:players,id|different:player2_id',
            'player2_id'             => 'required|exists:players,id',
            'stage'                  => 'required|string',
            'date'                   => 'required|date',
            'match_time'             => 'required',
            'set1_player1_points'    => 'required|integer',
            'set1_player2_points'    => 'required|integer',
            'set2_player1_points'    => 'required|integer',
            'set2_player2_points'    => 'required|integer',
            'set3_player1_points'    => 'nullable|integer',
            'set3_player2_points'    => 'nullable|integer',
        ]);

        Matches::create([
            'tournament_id'            => session('locked_tournament'),
            'category_id'              => $request->input('category_id'),
            'player1_id'               => $request->input('player1_id'),
            'player2_id'               => $request->input('player2_id'),
            'stage'                    => $request->input('stage'),
            'match_date'               => $request->input('date'),
            'match_time'               => $request->input('match_time'),
            'set1_player1_points'      => $request->input('set1_player1_points'),
            'set1_player2_points'      => $request->input('set1_player2_points'),
            'set2_player1_points'      => $request->input('set2_player1_points'),
            'set2_player2_points'      => $request->input('set2_player2_points'),
            'set3_player1_points'      => $request->input('set3_player1_points'),
            'set3_player2_points'      => $request->input('set3_player2_points'),
            'created_by'               => Auth::id(),
        ]);

        return redirect()->route('matches.singles.index')->with('success', 'Match successfully added!');
    }

    // ------------------------------------------------------------------
    // 4) Lock/Unlock
    // ------------------------------------------------------------------
    public function lockTournament(Request $request)
    {
        $request->validate(['tournament_id' => 'required|exists:tournaments,id']);
        $tournament = Tournament::findOrFail($request->tournament_id);

        session(['locked_tournament' => $tournament->id]);
        session(['locked_tournament_name' => $tournament->name]);

        return redirect()->back()->with('success', 'Tournament locked: ' . $tournament->name);
    }

    public function unlockTournament(Request $request)
    {
        session()->forget(['locked_tournament', 'locked_tournament_name']);
        return redirect()->back()->with('success', 'Tournament unlocked');
    }
}
