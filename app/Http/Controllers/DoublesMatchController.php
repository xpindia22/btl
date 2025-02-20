<?php

namespace App\Http\Controllers;

use App\Models\Matches;
use App\Models\Tournament;
use App\Models\Category;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoublesMatchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // ----------------------------------------
    // 1) Index: View Only
    // ----------------------------------------
    public function indexViewOnly()
    {
        // Example: find all doubles categories (BD, GD, XD)
        $user = Auth::user();
        $isAdmin = $user->is_admin;

        $matchesQuery = Matches::with(['tournament', 'category', 'player1', 'player2', 'player3', 'player4'])
            ->whereNull('deleted_at')
            ->whereHas('category', function ($query) {
                // if you store "BD", "GD", "XD" in the category name, do:
                $query->where('name', 'LIKE', '%BD%')
                      ->orWhere('name', 'LIKE', '%GD%')
                      ->orWhere('name', 'LIKE', '%XD%');
            });

        if (!$isAdmin) {
            $matchesQuery->where(function ($q) use ($user) {
                $q->where('created_by', $user->id)
                  ->orWhereHas('tournament.moderators', fn($q2) => $q2->where('user_id', $user->id));
            });
        }

        $matches = $matchesQuery->orderBy('id')->paginate(10);

        return view('matches.doubles.index', compact('matches'));
    }

    // ----------------------------------------
    // 2) Index: With Edit/Delete
    // ----------------------------------------
    public function indexWithEdit()
    {
        // Same as above, but returns a different Blade with editing enabled
        $user = Auth::user();
        $isAdmin = $user->is_admin;

        $matchesQuery = Matches::with(['tournament', 'category', 'player1', 'player2', 'player3', 'player4'])
            ->whereNull('deleted_at')
            ->whereHas('category', function ($query) {
                $query->where('name', 'LIKE', '%BD%')
                      ->orWhere('name', 'LIKE', '%GD%')
                      ->orWhere('name', 'LIKE', '%XD%');
            });

        if (!$isAdmin) {
            $matchesQuery->where(function ($q) use ($user) {
                $q->where('created_by', $user->id)
                  ->orWhereHas('tournament.moderators', fn($q2) => $q2->where('user_id', $user->id));
            });
        }

        $matches = $matchesQuery->orderBy('id')->paginate(10);

        return view('matches.doubles.edit', compact('matches'));
    }

    // ----------------------------------------
    // 3) Create Doubles
    // ----------------------------------------
    public function createDoubles(Request $request)
{
    $user = Auth::user();

    // Get available championships (or tournaments)
    // Adjust the query as needed; here we simply get all tournaments.
    $championships = Tournament::all();

    // Get tournaments accessible by the user (for locking)
    $lockedTournamentId = session('locked_tournament');
    $lockedTournament   = $lockedTournamentId ? Tournament::find($lockedTournamentId) : null;

    $players = Player::all();

    // Only show doubles categories (BD, GD, XD) for the locked tournament.
    $categories = [];
    if ($lockedTournamentId) {
        $categories = Category::whereHas('tournaments', function ($q) use ($lockedTournamentId) {
            $q->where('tournament_id', $lockedTournamentId);
        })
        ->where(function($query) {
            $query->where('name', 'LIKE', '%BD%')
                  ->orWhere('name', 'LIKE', '%GD%')
                  ->orWhere('name', 'LIKE', '%XD%');
        })
        ->get();
    }

    return view('matches.doubles.create', compact('championships', 'lockedTournament', 'players', 'categories'));
}

    // ----------------------------------------
    // 4) Store Doubles
    // ----------------------------------------
    public function storeDoubles(Request $request)
    {
        if (!session('locked_tournament')) {
            return redirect()->back()->withErrors('You must lock a tournament before adding a match.');
        }
    
        // Retrieve the selected category to determine doubles type.
        $category = Category::find($request->input('category_id'));
        if (!$category) {
            return redirect()->back()->withErrors('Category not found.');
        }
        $catName = strtoupper($category->name);
        $isMixed = (strpos($catName, 'XD') !== false) || (strpos($catName, 'MIXED') !== false);
    
        // Set up validation rules common to all doubles matches.
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
            // For mixed doubles, require separate male and female fields.
            $rules['team1_male']   = 'required';
            $rules['team1_female'] = 'required';
            $rules['team2_male']   = 'required';
            $rules['team2_female'] = 'required';
        } else {
            // For BD or GD, require the standard doubles fields.
            $rules['team1_player1'] = 'required|different:team1_player2';
            $rules['team1_player2'] = 'required';
            $rules['team2_player1'] = 'required|different:team2_player2';
            $rules['team2_player2'] = 'required';
        }
    
        $validated = $request->validate($rules);
    
        // Prepare the data array for insertion.
        $data = [
            'tournament_id'        => session('locked_tournament'),
            'category_id'          => $validated['category_id'],
            'stage'                => $validated['stage'],
            'match_date'           => $validated['date'],
            'match_time'           => $validated['match_time'],
            'set1_team1_points'    => $validated['set1_team1_points'],
            'set1_team2_points'    => $validated['set1_team2_points'],
            'set2_team1_points'    => $validated['set2_team1_points'],
            'set2_team2_points'    => $validated['set2_team2_points'],
            'set3_team1_points'    => $validated['set3_team1_points'] ?? null,
            'set3_team2_points'    => $validated['set3_team2_points'] ?? null,
            'created_by'           => Auth::id(),
        ];
    
        if ($isMixed) {
            $data['team1_player1_id'] = $validated['team1_male'];
            $data['team1_player2_id'] = $validated['team1_female'];
            $data['team2_player1_id'] = $validated['team2_male'];
            $data['team2_player2_id'] = $validated['team2_female'];
        } else {
            $data['team1_player1_id'] = $validated['team1_player1'];
            $data['team1_player2_id'] = $validated['team1_player2'];
            $data['team2_player1_id'] = $validated['team2_player1'];
            $data['team2_player2_id'] = $validated['team2_player2'];
        }
    
        // Debug: dump the data array.
        dd($data);
    
        // If the data looks correct, comment out the dd() and then create the record.
        \App\Models\MatchDoubles::create($data);
    
        return redirect()->route('matches.doubles.index')->with('success', 'Doubles match added!');
    }
    

    // ----------------------------------------
    // 5) getFilteredPlayers For Doubles
    // ----------------------------------------
    public function getFilteredPlayers(Request $request)
{
    $category = Category::find($request->category_id);
    if (!$category) {
        return response()->json(['error' => 'Category not found'], 404);
    }

    // Build the query for players
    $playersQuery = Player::query();

    // Determine category type by name. 
    // For BD: filter for male only; for GD: filter for female only.
    // For mixed doubles (XD) we do not filter by sex.
    $catName = strtoupper($category->name);
    if (strpos($catName, 'BD') !== false) {
        $playersQuery->where('sex', 'M');
    } elseif (strpos($catName, 'GD') !== false) {
        $playersQuery->where('sex', 'F');
    }
    
    // Apply age criteria based on the category's age_group string
    // Expected formats: "Under 16", "Over 35", "Between 20 - 35", "Open"
    $ageGroup = $category->age_group;
    if (str_starts_with($ageGroup, 'Under ')) {
        $limit = (int) str_replace('Under ', '', $ageGroup);
        $playersQuery->where('age', '<=', $limit);
    } elseif (str_starts_with($ageGroup, 'Over ')) {
        $limit = (int) str_replace('Over ', '', $ageGroup);
        $playersQuery->where('age', '>=', $limit);
    } elseif (str_starts_with($ageGroup, 'Between ')) {
        $rangePart = str_replace('Between ', '', $ageGroup); // e.g. "20 - 35"
        [$lower, $upper] = explode(' - ', $rangePart);
        $playersQuery->whereBetween('age', [(int)$lower, (int)$upper]);
    }
    // "Open" means no age filtering

    $players = $playersQuery->get();
    return response()->json($players);
}


public function lockTournament(Request $request)
{
    $request->validate(['tournament_id' => 'required|exists:tournaments,id']);
    $tournament = Tournament::findOrFail($request->tournament_id);

    session([
        'locked_tournament' => $tournament->id,
        'locked_tournament_name' => $tournament->name
    ]);

    return redirect()->back()->with('success', 'Championship locked: ' . $tournament->name);
}

public function unlockTournament(Request $request)
{
    session()->forget(['locked_tournament', 'locked_tournament_name']);
    return redirect()->back()->with('success', 'Championship unlocked');
}


}
