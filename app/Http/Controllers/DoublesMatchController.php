<?php

namespace App\Http\Controllers;

use App\Models\Matches;
use App\Models\Tournament;
use App\Models\Category;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DoublesMatchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // ----------------------------------------
    // 1) Create Doubles Match Form
    // ----------------------------------------
    public function createDoubles()
    {
        $user = Auth::user();

        // Get available championships (tournaments)
        $championships = Tournament::all();

        // Get tournaments accessible by the user (for locking)
        $lockedTournamentId = session('locked_tournament');
        $lockedTournament   = $lockedTournamentId ? Tournament::find($lockedTournamentId) : null;

        // Get only doubles categories (BD, GD, XD) for the locked tournament.
        $categories = [];
        if ($lockedTournamentId) {
            $categories = Category::whereHas('tournaments', function ($q) use ($lockedTournamentId) {
                $q->where('tournament_id', $lockedTournamentId);
            })
            ->where(function ($query) {
                $query->where('name', 'LIKE', '%BD%')
                      ->orWhere('name', 'LIKE', '%GD%')
                      ->orWhere('name', 'LIKE', '%XD%');
            })
            ->get();
        }

        return view('matches.doubles.create', compact('championships', 'lockedTournament', 'categories'));
    }

    // ----------------------------------------
    // 2) Store Doubles Match
    // ----------------------------------------
    public function storeDoubles(Request $request)
    {
        Log::info('StoreDoubles function started.');

        if (!session('locked_tournament')) {
            Log::error('Tournament not locked.');
            return redirect()->back()->withErrors('You must lock a tournament before adding a match.');
        }

        Log::info('Tournament locked: ' . session('locked_tournament'));

        $category = Category::find($request->input('category_id'));
        if (!$category) {
            Log::error('Category not found. ID: ' . $request->input('category_id'));
            return redirect()->back()->withErrors('Category not found.');
        }

        Log::info('Category selected: ' . $category->name);

        $catName = strtoupper($category->name);
        $isMixed = (strpos($catName, 'XD') !== false) || (strpos($catName, 'MIXED') !== false);

        $rules = [
            'category_id'         => 'required|exists:categories,id',
            'stage'               => 'required|string',
            'date'                => 'required|date',
            'match_time'          => 'required',
            'set1_team1_points'   => 'required|integer',
            'set1_team2_points'   => 'required|integer',
            'set2_team1_points'   => 'required|integer',
            'set2_team2_points'   => 'required|integer',
            'set3_team1_points'   => 'nullable|integer',
            'set3_team2_points'   => 'nullable|integer',
        ];

        if ($isMixed) {
            $rules['team1_male']   = 'required|exists:players,id';
            $rules['team1_female'] = 'required|exists:players,id';
            $rules['team2_male']   = 'required|exists:players,id';
            $rules['team2_female'] = 'required|exists:players,id';
        } else {
            $rules['team1_player1'] = 'required|different:team1_player2|exists:players,id';
            $rules['team1_player2'] = 'required|exists:players,id';
            $rules['team2_player1'] = 'required|different:team2_player2|exists:players,id';
            $rules['team2_player2'] = 'required|exists:players,id';
        }

        Log::info('Applying validation rules.');

        try {
            $validated = $request->validate($rules);
            Log::info('Validation passed.', $validated);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed: ' . json_encode($e->errors()));
            return redirect()->back()->withErrors($e->errors());
        }

        try {
            $match = new Matches();
            $match->tournament_id = session('locked_tournament');
            $match->category_id = $validated['category_id'];
            $match->stage = $validated['stage'];
            $match->match_date = $validated['date'];
            $match->match_time = $validated['match_time'];
            $match->set1_team1_points = $validated['set1_team1_points'];
            $match->set1_team2_points = $validated['set1_team2_points'];
            $match->set2_team1_points = $validated['set2_team1_points'];
            $match->set2_team2_points = $validated['set2_team2_points'];
            $match->set3_team1_points = $validated['set3_team1_points'] ?? null;
            $match->set3_team2_points = $validated['set3_team2_points'] ?? null;
            $match->created_by = Auth::id();

            if ($isMixed) {
                $match->team1_player1_id = $validated['team1_male'];
                $match->team1_player2_id = $validated['team1_female'];
                $match->team2_player1_id = $validated['team2_male'];
                $match->team2_player2_id = $validated['team2_female'];
            } else {
                $match->team1_player1_id = $validated['team1_player1'];
                $match->team1_player2_id = $validated['team1_player2'];
                $match->team2_player1_id = $validated['team2_player1'];
                $match->team2_player2_id = $validated['team2_player2'];
            }

            Log::info('Match data prepared for insertion.', $match->toArray());

            $match->save();
            Log::info('Match saved successfully.');

            return redirect()->route('matches.doubles.index')->with('success', 'Doubles match added!');
        } catch (\Exception $e) {
            Log::error('Error saving match: ' . $e->getMessage());
            return redirect()->back()->withErrors('Failed to save match. Error: ' . $e->getMessage());
        }
    }

    // ----------------------------------------
    // 3) Get Players for Doubles Match Based on Category
    // ----------------------------------------
    public function getFilteredPlayers(Request $request)
    {
        $category = Category::find($request->category_id);
        if (!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        $playersQuery = Player::query();
        $catName = strtoupper($category->name);

        if (strpos($catName, 'BD') !== false) {
            $playersQuery->where('sex', 'M');
        } elseif (strpos($catName, 'GD') !== false) {
            $playersQuery->where('sex', 'F');
        }

        return response()->json($playersQuery->select('id', 'name', 'age', 'sex')->get());
    }

    // ----------------------------------------
    // 4) Lock Tournament
    // ----------------------------------------
    public function lockTournament(Request $request)
    {
        $request->validate(['tournament_id' => 'required|exists:tournaments,id']);
        session(['locked_tournament' => $request->tournament_id]);
        return redirect()->back()->with('success', 'Championship locked.');
    }

    // ----------------------------------------
    // 5) Unlock Tournament
    // ----------------------------------------
    public function unlockTournament()
    {
        session()->forget('locked_tournament');
        return redirect()->back()->with('success', 'Championship unlocked.');
    }


    public function index(Request $request)
{
    // Get the selected filter category from dropdown
    $filterCategory = $request->input('filter_category', 'all'); // Default to 'all'

    // Query matches with necessary relationships
    $matchesQuery = Matches::with([
        'tournament',
        'category',
        'team1Player1',
        'team1Player2',
        'team2Player1',
        'team2Player2'
    ])->whereHas('category', function ($query) {
        $query->where('name', 'LIKE', '%BD%')
              ->orWhere('name', 'LIKE', '%GD%')
              ->orWhere('name', 'LIKE', '%XD%');
    });

    // Apply filtering if a specific category is selected
    if ($filterCategory !== 'all') {
        $matchesQuery->whereHas('category', function ($query) use ($filterCategory) {
            $query->where('name', 'LIKE', "%{$filterCategory}%");
        });
    }

    // Apply pagination (10 matches per page)
    $matches = $matchesQuery->orderBy('match_date', 'desc')->paginate(10);

    return view('matches.doubles.index', compact('matches', 'filterCategory'));
}


public function indexWithEdit(Request $request)
{
    // Get selected filter category
    $filterCategory = $request->input('filter_category', 'all');

    // Query matches
    $matchesQuery = Matches::with([
        'tournament',
        'category',
        'team1Player1',
        'team1Player2',
        'team2Player1',
        'team2Player2'
    ])->whereHas('category', function ($query) {
        $query->where('name', 'LIKE', '%BD%')
              ->orWhere('name', 'LIKE', '%GD%')
              ->orWhere('name', 'LIKE', '%XD%');
    });

    // Apply filter
    if ($filterCategory !== 'all') {
        $matchesQuery->whereHas('category', function ($query) use ($filterCategory) {
            $query->where('name', 'LIKE', "%{$filterCategory}%");
        });
    }

    // Fetch matches with pagination
    $matches = $matchesQuery->orderBy('match_date', 'desc')->paginate(10);

    return view('matches.doubles.edit', compact('matches', 'filterCategory'));
}



public function updateMultiple(Request $request)
{
    foreach ($request->matches as $matchId => $matchData) {
        $match = Matches::find($matchId);
        if ($match) {
            $match->update($matchData);
        }
    }

    return redirect()->back()->with('success', 'Matches updated successfully!');
}

public function update(Request $request, $id)
{
    // Find the match, or return error if not found
    $match = Matches::find($id);
    if (!$match) {
        return redirect()->back()->withErrors('Match not found.');
    }

    // Validate incoming request data
    $validated = $request->validate([
        'match_date' => 'required|date',
        'match_time' => 'required',
        'stage' => 'required|string',
        'set1_team1_points' => 'nullable|integer',
        'set1_team2_points' => 'nullable|integer',
        'set2_team1_points' => 'nullable|integer',
        'set2_team2_points' => 'nullable|integer',
        'set3_team1_points' => 'nullable|integer',
        'set3_team2_points' => 'nullable|integer',
    ]);

    // Update match details
    $match->update($validated);

    return redirect()->route('matches.doubles.edit')->with('success', 'Match updated successfully!');
}

public function softDelete($id)
{
    $match = Matches::find($id);

    if (!$match) {
        return redirect()->back()->withErrors('Match not found.');
    }

    $match->delete(); // Soft delete the match

    return redirect()->route('matches.doubles.edit')->with('success', 'Match soft deleted successfully!');
}


}
