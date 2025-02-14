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

        $tournaments = Tournament::where('created_by', $user->id)
            ->orWhereHas('moderators', fn ($q) => $q->where('user_id', $user->id))
            ->get();

        $lockedTournamentId = session('locked_tournament');
        $lockedTournament = $lockedTournamentId ? Tournament::find($lockedTournamentId) : null;

        $categories = $lockedTournament
            ? Category::join('tournament_categories', 'categories.id', '=', 'tournament_categories.category_id')
                ->where('tournament_categories.tournament_id', $lockedTournamentId)
                ->where('categories.name', 'like', '%BD%') // Boys Doubles categories
                ->select('categories.*')
                ->get()
            : collect();

        $players = Player::all();

        return view('matches.doubles_boys.create', compact('tournaments', 'categories', 'lockedTournament', 'players'));
    }

    /**
     * Lock the selected tournament.
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

        session(['locked_tournament' => $tournament->id]);

        return redirect()->back()->with('message', "Tournament '{$tournament->name}' locked.");
    }

    /**
     * Unlock the tournament.
     */
    public function unlockTournament()
    {
        session()->forget('locked_tournament');

        return redirect()->back()->with('message', 'Tournament unlocked successfully.');
    }

    /**
     * Store a new Boys Doubles match.
     */
    public function store(Request $request)
    {
        try {
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
                'created_by'        => Auth::id(),
            ]);

            return back()->with('message', "Match added successfully!");

        } catch (\Exception $e) {
            \Log::error("Error storing match: " . $e->getMessage());
            return back()->with('error', "Error adding match. Check logs.");
        }
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
        ]);

        return redirect()->route('matches.doubles_boys.index')->with('message', 'Match updated successfully!');
    }

    /**
     * Delete a Boys Doubles match.
     */
    public function destroy($id)
    {
        Matches_bd::findOrFail($id)->delete();

        return redirect()->route('matches.doubles_boys.index')->with('message', 'Match deleted successfully!');
    }

    /**
     * Ensure match time is in HH:MM:SS format.
     */
    private function formatMatchTime($timeInput)
    {
        return substr_count($timeInput, ':') === 1 ? $timeInput . ':00' : $timeInput;
    }
}
