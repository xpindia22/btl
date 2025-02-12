<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Matches;
use App\Models\Tournament;
use App\Models\Category;
use App\Models\Player;
use Illuminate\Support\Facades\Log;

class MatchController extends Controller
{
    /**
     * Display a listing of the matches.
     */
    public function index()
    {
        $matches = Matches::all();
        $lockedTournament = session('locked_tournament', null);
        $tournaments = Tournament::all();
        $categories = [];
        $players = [];
        if ($lockedTournament) {
            $categories = Category::join('tournament_categories', 'categories.id', '=', 'tournament_categories.category_id')
                ->where('tournament_categories.tournament_id', $lockedTournament)
                ->get();
            $players = Player::all();
        }
        return view('matches.index', compact('matches', 'lockedTournament', 'tournaments', 'categories', 'players'));
    }

    /**
     * Show the match creation form.
     */
    public function createSingles()
{
    $lockedTournament = session('locked_tournament', null);
    $tournaments = Tournament::all();
    $categories = [];
    
    if ($lockedTournament) {
        $categories = Category::join('tournament_categories', 'categories.id', '=', 'tournament_categories.category_id')
            ->where('tournament_categories.tournament_id', $lockedTournament)
            ->get();
    }

    $players = Player::all();

    return view('matches.singles.create', compact('lockedTournament', 'tournaments', 'categories', 'players')); // ✅ Updated path
}


    /**
     * Store a newly created match in storage.
     */
    public function store(Request $request)
    {
        if ($request->has('lock_tournament')) {
            $lockedTournament = intval($request->input('tournament_id'));
            session(['locked_tournament' => $lockedTournament]);
            $tournament = Tournament::find($lockedTournament);
            session(['locked_tournament_name' => $tournament->name ?? null]);
            return redirect()->back();
        } elseif ($request->has('unlock_tournament')) {
            session()->forget(['locked_tournament', 'locked_tournament_name']);
            return redirect()->back();
        }
        $validated = $request->validate([
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
        $matchTime = $validated['match_time'] . ':00';
        try {
            $match = new Matches();
            $match->tournament_id = session('locked_tournament');
            $match->category_id = $validated['category_id'];
            $match->player1_id = $validated['player1_id'];
            $match->player2_id = $validated['player2_id'];
            $match->stage = $validated['stage'];
            $match->match_date = $validated['date'];
            $match->match_time = $matchTime;
            $match->set1_player1_points = $validated['set1_player1_points'];
            $match->set1_player2_points = $validated['set1_player2_points'];
            $match->set2_player1_points = $validated['set2_player1_points'];
            $match->set2_player2_points = $validated['set2_player2_points'];
            $match->set3_player1_points = $validated['set3_player1_points'];
            $match->set3_player2_points = $validated['set3_player2_points'];
            $match->save();
            return redirect()->route('matches.index')->with('message', 'Match successfully added!');
        } catch (\Exception $e) {
            Log::error("Error inserting match: " . $e->getMessage());
            return redirect()->back()->with('message', 'Error inserting match: ' . $e->getMessage());
        }
    }
}
