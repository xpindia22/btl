<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tournament;
use App\Models\Category;
use App\Models\Matches_xd; // Your mixed doubles match model
use Carbon\Carbon;
use DB;

class DoublesMixedMatchController extends Controller
{
    /**
     * Display the form for creating a new mixed doubles match.
     */
    public function create(Request $request)
    {
        $user = Auth::user();

        // Get tournaments created by or moderated by the user
        $tournaments = Tournament::where('created_by', $user->id)
            ->orWhereHas('moderators', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->get();

        // Get locked tournament ID from session, if any
        $lockedTournamentId = session('locked_tournament');
        if ($lockedTournamentId) {
            // Get categories (e.g., those with 'XD' in the name) for the locked tournament.
            $categories = Category::join('tournament_categories', 'categories.id', '=', 'tournament_categories.category_id')
                ->where('tournament_categories.tournament_id', $lockedTournamentId)
                ->where('categories.name', 'like', '%XD%')
                ->select('categories.*')
                ->get();
        } else {
            $categories = collect();
        }

        return view('matches.doubles_mixed.create', compact('tournaments', 'categories', 'lockedTournamentId'));
    }

    /**
     * Store a new mixed doubles match or handle tournament locking.
     */
    public function store(Request $request)
{
    // Debug: Dump request data to inspect submitted fields.
    // dd($request->all()); // Remove or comment this line after debugging.

    // If tournament_id is missing, merge it from session
    if (!$request->has('tournament_id')) {
        $request->merge(['tournament_id' => session('locked_tournament')]);
    }
    
    $user = Auth::user();

    // Handle tournament locking/unlocking if applicable...
    if ($request->has('lock_tournament')) {
        // ... existing code for locking ...
    } elseif ($request->has('unlock_tournament')) {
        // ... existing code for unlocking ...
    }

    // Validate the submitted match data.
    $validated = $request->validate([
        'tournament_id'      => 'required|integer',
        'category_id'        => 'required|integer',
        'team1_player1_id'   => 'required|integer',
        'team1_player2_id'   => 'required|integer',
        'team2_player1_id'   => 'required|integer',
        'team2_player2_id'   => 'required|integer',
        'stage'              => 'required|string',
        'date'               => 'required|date_format:Y-m-d',
        'time'               => 'required',
        'set1_team1_points'  => 'required|integer',
        'set1_team2_points'  => 'required|integer',
        'set2_team1_points'  => 'required|integer',
        'set2_team2_points'  => 'required|integer',
        // set3 points are optional
    ]);

    // Format the date and time
    $match_date = Carbon::createFromFormat('Y-m-d', $validated['date'])->format('Y-m-d');
    $match_time = $request->input('time');
    if (substr_count($match_time, ':') === 1) {
        $match_time .= ':00';
    }

    // Create and save the match record.
    $match = new Matches_xd();
    $match->tournament_id      = $request->input('tournament_id');
    $match->category_id        = $request->input('category_id');
    $match->team1_player1_id   = $request->input('team1_player1_id');
    $match->team1_player2_id   = $request->input('team1_player2_id');
    $match->team2_player1_id   = $request->input('team2_player1_id');
    $match->team2_player2_id   = $request->input('team2_player2_id');
    $match->stage              = $validated['stage'];
    $match->match_date         = $match_date;
    $match->match_time         = $match_time;
    $match->set1_team1_points  = $request->input('set1_team1_points');
    $match->set1_team2_points  = $request->input('set1_team2_points');
    $match->set2_team1_points  = $request->input('set2_team1_points');
    $match->set2_team2_points  = $request->input('set2_team2_points');
    $match->set3_team1_points  = $request->input('set3_team1_points', 0);
    $match->set3_team2_points  = $request->input('set3_team2_points', 0);
    $match->created_by         = $user->id;

    if ($match->save()) {
        return redirect()->back()->with('message', "Match added successfully!");
    } else {
        return redirect()->back()->with('message', "Error adding match.");
    }
}

public function editResults()
{
    $user = Auth::user();

    // Query mixed doubles matches with joins to get tournament, category, and player names.
    $matches = DB::table('matches as m')
        ->join('tournaments as t', 'm.tournament_id', '=', 't.id')
        ->join('categories as c', 'm.category_id', '=', 'c.id')
        ->leftJoin('players as p1', 'm.team1_player1_id', '=', 'p1.id')
        ->leftJoin('players as p2', 'm.team1_player2_id', '=', 'p2.id')
        ->leftJoin('players as p3', 'm.team2_player1_id', '=', 'p3.id')
        ->leftJoin('players as p4', 'm.team2_player2_id', '=', 'p4.id')
        ->select(
            'm.id as match_id',
            't.name as tournament_name',
            'c.name as category_name',
            'p1.name as team1_player1_name',
            'p2.name as team1_player2_name',
            'p3.name as team2_player1_name',
            'p4.name as team2_player2_name',
            'm.stage',
            'm.match_date',
            'm.match_time',
            'm.set1_team1_points',
            'm.set1_team2_points',
            'm.set2_team1_points',
            'm.set2_team2_points',
            'm.set3_team1_points',
            'm.set3_team2_points'
        )
        ->where('c.type', 'mixed doubles')
        ->where(function ($q) use ($user) {
            $q->where('m.created_by', $user->id)
              ->orWhereExists(function ($query) use ($user) {
                  $query->select(DB::raw(1))
                        ->from('tournament_moderators as tm')
                        ->join('tournaments as t2', 'tm.tournament_id', '=', 't2.id')
                        ->whereRaw('t2.id = m.tournament_id')
                        ->where('tm.user_id', $user->id);
              });
        })
        ->orderBy('m.id')
        ->get();

    // Define available stages for the dropdown.
    $stages = ['Pre Quarter Finals', 'Quarter Finals', 'Semi Finals', 'Finals'];

    return view('matches.doubles_mixed.edit_results', compact('matches', 'stages'));
}


public function update(Request $request, $id)
{
    $user = Auth::user();

    // Validate incoming data.
    $validated = $request->validate([
        'stage'             => 'required|string',
        'match_date'        => 'required|date_format:Y-m-d',
        'match_time'        => 'required',
        'set1_team1_points' => 'required|integer',
        'set1_team2_points' => 'required|integer',
        'set2_team1_points' => 'required|integer',
        'set2_team2_points' => 'required|integer',
        'set3_team1_points' => 'nullable|integer',
        'set3_team2_points' => 'nullable|integer',
    ]);

    // Format match time (append seconds if needed)
    $match_time = $request->input('match_time');
    if (substr_count($match_time, ':') === 1) {
        $match_time .= ':00';
    }

    // Retrieve the match record.
    $match = \App\Models\Matches_xd::findOrFail($id);

    // Authorization:
    // 1. Admins can update any match.
    // 2. Non-admin users must either be the match creator or a moderator for the tournament.
    if ($user->role !== 'admin') {
        if ($match->created_by !== $user->id) {
            $isModerator = DB::table('tournament_moderators')
                ->where('tournament_id', $match->tournament_id)
                ->where('user_id', $user->id)
                ->exists();

            if (!$isModerator) {
                return redirect()->back()->with('message', 'Unauthorized access.');
            }
        }
    }

    // Update the match record.
    $match->stage             = $validated['stage'];
    $match->match_date        = $validated['match_date'];
    $match->match_time        = $match_time;
    $match->set1_team1_points = $request->input('set1_team1_points');
    $match->set1_team2_points = $request->input('set1_team2_points');
    $match->set2_team1_points = $request->input('set2_team1_points');
    $match->set2_team2_points = $request->input('set2_team2_points');
    $match->set3_team1_points = $request->input('set3_team1_points', 0);
    $match->set3_team2_points = $request->input('set3_team2_points', 0);

    $match->save();

    return redirect()->back()->with('message', 'Match updated successfully!');
}

}
