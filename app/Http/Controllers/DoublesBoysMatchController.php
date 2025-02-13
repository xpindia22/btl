<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tournament;
use App\Models\Category;
use App\Models\Matches_bd;
use App\Models\Player;
use Carbon\Carbon;

class DoublesBoysMatchController extends Controller
{
    /**
     * Format match time to HH:MM:SS if not already in that format.
     */
    protected function formatMatchTime($timeInput)
    {
        return substr_count($timeInput, ':') === 1 ? $timeInput . ':00' : $timeInput;
    }

    /**
     * Display a list of Boys Doubles matches.
     */
    public function index()
    {
        $user = Auth::user();

        $matches = Matches_bd::with(['tournament', 'category', 'team1Player1', 'team1Player2', 'team2Player1', 'team2Player2'])
            ->whereHas('category', function ($q) {
                $q->where('type', 'doubles')->where('sex', 'M');
            })
            ->where(function ($q) use ($user) {
                $q->where('created_by', $user->id)
                  ->orWhereHas('tournament.moderators', fn ($q) => $q->where('user_id', $user->id));
            })
            ->orderBy('id')
            ->get();

        $stages = ['Pre Quarter Finals', 'Quarter Finals', 'Semifinals', 'Finals', 'Preliminary'];

        return view('matches.doubles_boys.edit_results', compact('matches', 'stages'));
    }

    /**
     * Show the create form for a Boys Doubles match.
     */
    public function create()
    {
        $user = Auth::user();

        // Fetch user's tournaments (created by or moderated by the user)
        $tournaments = Tournament::where('created_by', $user->id)
            ->orWhereHas('moderators', fn ($q) => $q->where('user_id', $user->id))
            ->get();

        // Retrieve locked tournament from session
        $lockedTournamentId = session('locked_tournament');
        $lockedTournament = $lockedTournamentId ? Tournament::find($lockedTournamentId) : null;

        // Fetch categories only if a tournament is locked
        $categories = $lockedTournament
            ? Category::join('tournament_categories', 'categories.id', '=', 'tournament_categories.category_id')
                ->where('tournament_categories.tournament_id', $lockedTournamentId)
                ->where('categories.name', 'like', '%BD%') // Boys Doubles categories
                ->select('categories.*')
                ->get()
            : collect();

        // Fetch all players (modify this query as per your database structure)
        $players = Player::all();

        return view('matches.doubles_boys.create', compact('tournaments', 'categories', 'lockedTournament', 'players'));
    }

    /**
     * Store a new Boys Doubles match.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Tournament Locking / Unlocking Mechanism
        if ($request->has('lock_tournament')) {
            $lockedTournamentId = intval($request->input('tournament_id'));
            session(['locked_tournament' => $lockedTournamentId]);

            $tournament = Tournament::where('id', $lockedTournamentId)
                ->where(fn ($query) => $query->where('created_by', $user->id)
                    ->orWhereHas('moderators', fn ($q) => $q->where('user_id', $user->id)))
                ->first();

            if ($tournament) {
                session(['locked_tournament_name' => $tournament->name]);
                return back()->with('message', "Tournament locked: " . e($tournament->name));
            } else {
                session()->forget('locked_tournament');
                return back()->with('error', "Unauthorized access to the selected tournament.");
            }
        } elseif ($request->has('unlock_tournament')) {
            session()->forget(['locked_tournament', 'locked_tournament_name']);
            return back();
        }

        // Validate form input
        $validated = $request->validate([
            'tournament_id'  => 'required|integer|exists:tournaments,id',
            'category_id'    => 'required|integer|exists:categories,id',
            'stage'          => 'required|string',
            'date'           => 'required|date_format:Y-m-d',
            'time'           => 'required',
            'team1_player1_id' => 'required|integer|exists:players,id|different:team1_player2_id',
            'team1_player2_id' => 'required|integer|exists:players,id',
            'team2_player1_id' => 'required|integer|exists:players,id|different:team2_player2_id',
            'team2_player2_id' => 'required|integer|exists:players,id',
        ]);

        // Store match details
        $match = Matches_bd::create([
            'tournament_id'     => $validated['tournament_id'],
            'category_id'       => $validated['category_id'],
            'team1_player1_id'  => $validated['team1_player1_id'],
            'team1_player2_id'  => $validated['team1_player2_id'],
            'team2_player1_id'  => $validated['team2_player1_id'],
            'team2_player2_id'  => $validated['team2_player2_id'],
            'stage'             => $validated['stage'],
            'match_date'        => Carbon::parse($validated['date'])->format('Y-m-d'),
            'match_time'        => $this->formatMatchTime($validated['time']),
            'set1_team1_points' => $request->input('set1_team1_points', 0),
            'set1_team2_points' => $request->input('set1_team2_points', 0),
            'set2_team1_points' => $request->input('set2_team1_points', 0),
            'set2_team2_points' => $request->input('set2_team2_points', 0),
            'set3_team1_points' => $request->input('set3_team1_points', 0),
            'set3_team2_points' => $request->input('set3_team2_points', 0),
            'created_by'        => $user->id,
        ]);

        return $match
            ? back()->with('message', "Match added successfully!")
            : back()->with('error', "Error adding match.");
    }

    /**
     * Update an existing Boys Doubles match.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'stage'      => 'required|string',
            'match_date' => 'required|date_format:Y-m-d',
            'match_time' => 'required',
        ]);

        $match = Matches_bd::findOrFail($id);
        $match->update([
            'stage'             => $validated['stage'],
            'match_date'        => Carbon::parse($validated['match_date'])->format('Y-m-d'),
            'match_time'        => $this->formatMatchTime($validated['match_time']),
            'set1_team1_points' => $request->input('set1_team1_points', 0),
            'set1_team2_points' => $request->input('set1_team2_points', 0),
            'set2_team1_points' => $request->input('set2_team1_points', 0),
            'set2_team2_points' => $request->input('set2_team2_points', 0),
            'set3_team1_points' => $request->input('set3_team1_points', 0),
            'set3_team2_points' => $request->input('set3_team2_points', 0),
        ]);

        return redirect()->route('matches.doubles_boys.index')->with('message', 'Match updated successfully!');
    }

    /**
     * Delete a Boys Doubles match.
     */
    public function destroy($id)
    {
        $match = Matches_bd::findOrFail($id);

        return $match->delete()
            ? redirect()->route('matches.doubles_boys.index')->with('message', 'Match deleted successfully!')
            : redirect()->route('matches.doubles_boys.index')->with('error', 'Error deleting match.');
    }
}
