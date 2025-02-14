<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Tournament;
use App\Models\Category;
use App\Models\Matches_gd; // Updated model name
use App\Models\Player;
use Carbon\Carbon;

class DoublesGirlsMatchController extends Controller
{
    /**
     * Format match time to HH:MM:SS.
     */
    protected function formatMatchTime($timeInput)
    {
        return substr_count($timeInput, ':') === 1 ? $timeInput . ':00' : $timeInput;
    }

    /**
     * Display a list of Girls Doubles matches.
     */
    public function index()
    {
        $user = Auth::user();

        $matches = Matches_gd::with(['tournament', 'category', 'team1Player1', 'team1Player2', 'team2Player1', 'team2Player2'])
            ->whereHas('category', function ($q) {
                $q->where('type', 'doubles')->where('sex', 'F'); // Ensure only Girls Doubles
            })
            ->where(function ($q) use ($user) {
                $q->where('created_by', $user->id)
                  ->orWhereHas('tournament.moderators', fn ($q) => $q->where('user_id', $user->id));
            })
            ->orderBy('id')
            ->get();

        $stages = ['Pre Quarter Finals', 'Quarter Finals', 'Semifinals', 'Finals', 'Preliminary'];

        return view('matches.doubles_girls.edit_results', compact('matches', 'stages'));
    }

    /**
     * Show the create form for Girls Doubles match.
     */
    public function create(Request $request)
    {
        $user = Auth::user();

        // Fetch user's tournaments (created by or moderated by the user)
        $tournaments = Tournament::where('created_by', $user->id)
            ->orWhereHas('moderators', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->get();

        // Retrieve locked tournament from session
        $lockedTournamentId = session('locked_tournament', null);
        $lockedTournament = $lockedTournamentId ? Tournament::find($lockedTournamentId) : null;

        // Fetch categories only if a tournament is locked
        $categories = $lockedTournament
            ? Category::join('tournament_categories', 'categories.id', '=', 'tournament_categories.category_id')
                ->where('tournament_categories.tournament_id', $lockedTournamentId)
                ->where('categories.name', 'like', '%GD%') // Girls Doubles categories
                ->select('categories.*')
                ->get()
            : collect();

        // Fetch all players
        $players = Player::all();

        return view('matches.doubles_girls.create', compact('tournaments', 'categories', 'lockedTournament', 'players'));
    }

    /**
     * Lock a tournament.
     */
    public function lockTournament(Request $request)
    {
        $user = Auth::user();
        $tournamentId = $request->input('tournament_id');

        $tournament = Tournament::where('id', $tournamentId)
            ->where(function ($query) use ($user) {
                $query->where('created_by', $user->id)
                      ->orWhereHas('moderators', fn ($q) => $q->where('user_id', $user->id));
            })
            ->first();

        if (!$tournament) {
            return redirect()->back()->with('error', 'Unauthorized access to lock this tournament.');
        }

        session(['locked_tournament' => $tournament->id, 'locked_tournament_name' => $tournament->name]);

        return redirect()->back()->with('message', "Tournament '{$tournament->name}' locked.");
    }

    /**
     * Unlock the tournament.
     */
    public function unlockTournament()
    {
        session()->forget(['locked_tournament', 'locked_tournament_name']);

        return redirect()->back()->with('message', 'Tournament unlocked successfully.');
    }

    /**
     * Store a new Girls Doubles match.
     */
    public function store(Request $request)
    {
        try {
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
                'set1_team1_points' => 'nullable|integer|min:0',
                'set1_team2_points' => 'nullable|integer|min:0',
                'set2_team1_points' => 'nullable|integer|min:0',
                'set2_team2_points' => 'nullable|integer|min:0',
                'set3_team1_points' => 'nullable|integer|min:0',
                'set3_team2_points' => 'nullable|integer|min:0',
            ]);

            // Store match details
            Matches_gd::create([
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
                'created_by'        => Auth::id(),
            ]);

            return back()->with('message', "Match added successfully!");

        } catch (\Exception $e) {
            Log::error("Error storing match: " . $e->getMessage());
            return back()->with('error', "Error adding match. Check logs.");
        }
    }
}
