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

}
