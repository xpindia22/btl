<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tournament;
use App\Models\Category;
use App\Models\Matches_xd;
use Carbon\Carbon;
use DB;

class DoublesMixedMatchController extends Controller
{
    /**
     * Display all mixed doubles matches.
     */
    public function index()
{
    $user = Auth::user();

    // Fetch all mixed doubles matches
    $matches = Matches_xd::with([
        'tournament',
        'category',
        'team1Player1',
        'team1Player2',
        'team2Player1',
        'team2Player2'
    ])
    ->whereHas('category', function ($query) {
        $query->where('name', 'like', '%XD%');
    })
    ->orderBy('match_date', 'desc')
    ->get();

    return view('matches.doubles_mixed.index', compact('matches'));
}

    /**
     * Show the form to create a new mixed doubles match.
     */
    public function create()
    {
        $user = Auth::user();

        // Get tournaments created by or moderated by the user
        $tournaments = Tournament::where('created_by', $user->id)
            ->orWhereHas('moderators', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->get();

        // Get categories for mixed doubles
        $categories = Category::where('name', 'like', '%XD%')->get();

        return view('matches.doubles_mixed.create', compact('tournaments', 'categories'));
    }

    /**
     * Store a new mixed doubles match.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Validate match data
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
            'set3_team1_points'  => 'nullable|integer',
            'set3_team2_points'  => 'nullable|integer',
        ]);

        // Format date and time
        $match_date = Carbon::parse($validated['date'])->format('Y-m-d');
        $match_time = Carbon::parse($validated['time'])->format('H:i:s');

        // Create the match
        Matches_xd::create([
            'tournament_id' => $validated['tournament_id'],
            'category_id' => $validated['category_id'],
            'team1_player1_id' => $validated['team1_player1_id'],
            'team1_player2_id' => $validated['team1_player2_id'],
            'team2_player1_id' => $validated['team2_player1_id'],
            'team2_player2_id' => $validated['team2_player2_id'],
            'stage' => $validated['stage'],
            'match_date' => $match_date,
            'match_time' => $match_time,
            'set1_team1_points' => $validated['set1_team1_points'],
            'set1_team2_points' => $validated['set1_team2_points'],
            'set2_team1_points' => $validated['set2_team1_points'],
            'set2_team2_points' => $validated['set2_team2_points'],
            'set3_team1_points' => $validated['set3_team1_points'] ?? 0,
            'set3_team2_points' => $validated['set3_team2_points'] ?? 0,
            'created_by' => $user->id,
        ]);

        return redirect()->back()->with('message', "Match added successfully!");
    }

    /**
     * Show the edit page for mixed doubles matches.
     */
    public function edit()
{
    $user = Auth::user();

    // Fetch all mixed doubles matches
    $matches = Matches_xd::with([
        'tournament',
        'category',
        'team1Player1',
        'team1Player2',
        'team2Player1',
        'team2Player2'
    ])
    ->whereHas('category', function ($query) {
        $query->where('name', 'like', '%XD%');
    })
    ->where(function ($query) use ($user) {
        $query->where('created_by', $user->id)
            ->orWhereHas('tournament.moderators', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
    })
    ->orderBy('match_date', 'desc')
    ->get();

    // ✅ Ensure scores are passed
    foreach ($matches as $match) {
        $match->set1_team1_points = $match->set1_team1_points ?? 0;
        $match->set1_team2_points = $match->set1_team2_points ?? 0;
        $match->set2_team1_points = $match->set2_team1_points ?? 0;
        $match->set2_team2_points = $match->set2_team2_points ?? 0;
        $match->set3_team1_points = $match->set3_team1_points ?? 0;
        $match->set3_team2_points = $match->set3_team2_points ?? 0;
    }

    // ✅ Define available match stages
    $stages = ['Pre Quarter Finals', 'Quarter Finals', 'Semi Finals', 'Finals'];

    return view('matches.doubles_mixed.edit', compact('matches', 'stages'));
}


    /**
     * Update a mixed doubles match.
     */
    public function update(Request $request, $id)
    {
        $match = Matches_xd::findOrFail($id);

        // Validate data
        $validated = $request->validate([
            'stage' => 'required|string',
            'match_date' => 'required|date_format:Y-m-d',
            'match_time' => 'required',
            'set1_team1_points' => 'required|integer',
            'set1_team2_points' => 'required|integer',
            'set2_team1_points' => 'required|integer',
            'set2_team2_points' => 'required|integer',
            'set3_team1_points' => 'nullable|integer',
            'set3_team2_points' => 'nullable|integer',
        ]);

        // Format time
        $match_time = Carbon::parse($validated['match_time'])->format('H:i:s');

        // Update match details
        $match->update([
            'stage'             => $validated['stage'],
            'match_date'        => $validated['match_date'],
            'match_time'        => $match_time,
            'set1_team1_points' => $validated['set1_team1_points'],
            'set1_team2_points' => $validated['set1_team2_points'],
            'set2_team1_points' => $validated['set2_team1_points'],
            'set2_team2_points' => $validated['set2_team2_points'],
            'set3_team1_points' => $validated['set3_team1_points'] ?? 0,
            'set3_team2_points' => $validated['set3_team2_points'] ?? 0,
        ]);

        return redirect()->back()->with('message', 'Match updated successfully!');
    }

    /**
     * Delete a mixed doubles match.
     */
    public function destroy($id)
    {
        $match = Matches_xd::findOrFail($id);
        $match->delete();

        return redirect()->back()->with('message', 'Match deleted successfully!');
    }

    public function indexViewOnly()
    {
        $matches = Matches_xd::with(['tournament', 'category', 'team1Player1', 'team1Player2', 'team2Player1', 'team2Player2'])
            ->whereHas('category', function ($q) {
                $q->where('name', 'like', '%XD%');
            })
            ->orderBy('id')
            ->get();

        return view('matches.doubles_mixed.index', compact('matches'));
    }

    /**
     * Editable Mixed Doubles Matches (With Edit/Delete)
     */
    public function indexWithEdit()
    {
        $user = Auth::user();
        $matches = Matches_xd::with(['tournament', 'category', 'team1Player1', 'team1Player2', 'team2Player1', 'team2Player2'])
            ->whereHas('category', function ($q) {
                $q->where('name', 'like', '%XD%');
            });

        if (!$user->is_admin) {
            $matches->where(function ($q) use ($user) {
                $q->where('created_by', $user->id)
                  ->orWhereHas('tournament.moderators', fn ($q2) => $q2->where('user_id', $user->id));
            });
        }

        $matches = $matches->orderBy('id')->get();

        return view('matches.doubles_mixed.edit', compact('matches'));
    }
}
