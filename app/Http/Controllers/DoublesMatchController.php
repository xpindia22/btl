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
    // Fetch all tournaments and players
    $tournaments = Tournament::all();
    $players = Player::all();

    // Get selected filters
    $filterTournament = $request->input('filter_tournament', 'all');
    $filterCategory = $request->input('filter_category', 'all');
    $filterPlayer = $request->input('filter_player', 'all');
    $filterDate = $request->input('filter_date', '');
    $filterStage = $request->input('filter_stage', 'all');
    $filterResults = $request->input('filter_results', 'all');

    // Query matches
    $matchesQuery = Matches::with(['tournament', 'category', 'team1Player1', 'team1Player2', 'team2Player1', 'team2Player2'])
        ->whereNull('deleted_at');

    // Apply filters
    if ($filterTournament !== 'all') {
        $matchesQuery->where('tournament_id', $filterTournament);
    }

    if ($filterCategory !== 'all') {
        $matchesQuery->whereHas('category', function ($query) use ($filterCategory) {
            $query->where('name', 'LIKE', "%{$filterCategory}%");
        });
    }

    if ($filterPlayer !== 'all') {
        $matchesQuery->where(function ($query) use ($filterPlayer) {
            $query->where('team1_player1_id', $filterPlayer)
                  ->orWhere('team1_player2_id', $filterPlayer)
                  ->orWhere('team2_player1_id', $filterPlayer)
                  ->orWhere('team2_player2_id', $filterPlayer);
        });
    }

    if (!empty($filterDate)) {
        $matchesQuery->whereDate('match_date', $filterDate);
    }

    if ($filterStage !== 'all') {
        $matchesQuery->where('stage', $filterStage);
    }

    // **Fix: Apply Results Filter**
    if ($filterResults !== 'all') {
        $matchesQuery->where(function ($query) use ($filterResults) {
            if ($filterResults === 'Team 1') {
                $query->whereRaw("
                    (set1_team1_points > set1_team2_points) +
                    (set2_team1_points > set2_team2_points) +
                    (IFNULL(set3_team1_points, 0) > IFNULL(set3_team2_points, 0))
                    >
                    (set1_team2_points > set1_team1_points) +
                    (set2_team2_points > set2_team1_points) +
                    (IFNULL(set3_team2_points, 0) > IFNULL(set3_team1_points, 0))
                ");
            } elseif ($filterResults === 'Team 2') {
                $query->whereRaw("
                    (set1_team2_points > set1_team1_points) +
                    (set2_team2_points > set2_team1_points) +
                    (IFNULL(set3_team2_points, 0) > IFNULL(set3_team1_points, 0))
                    >
                    (set1_team1_points > set1_team2_points) +
                    (set2_team1_points > set2_team2_points) +
                    (IFNULL(set3_team1_points, 0) > IFNULL(set3_team2_points, 0))
                ");
            } elseif ($filterResults === 'Draw') {
                $query->whereRaw("
                    (set1_team1_points = set1_team2_points) +
                    (set2_team1_points = set2_team2_points) +
                    (IFNULL(set3_team1_points, 0) = IFNULL(set3_team2_points, 0)) = 3
                ");
            }
        });
    }

    // Fetch matches with pagination
    $matches = $matchesQuery->orderBy('match_date', 'desc')->paginate(10);

    return view('matches.doubles.index', compact(
        'matches', 'tournaments', 'players', 'filterTournament', 'filterCategory', 'filterPlayer', 'filterDate', 'filterStage', 'filterResults'
    ));
}
public function indexWithEdit(Request $request)
{
    // Fetch all tournaments and players
    $tournaments = Tournament::all();
    $players = Player::all();

    // Get selected filters
    $filterTournament = $request->input('filter_tournament', 'all');
    $filterCategory = $request->input('filter_category', 'all');
    $filterPlayer = $request->input('filter_player', 'all');
    $filterDate = $request->input('filter_date', '');
    $filterStage = $request->input('filter_stage', 'all');
    $filterResults = $request->input('filter_results', 'all');

    // Query matches
    $matchesQuery = Matches::with(['tournament', 'category', 'team1Player1', 'team1Player2', 'team2Player1', 'team2Player2'])
        ->whereNull('deleted_at')
        ->whereHas('category', function ($query) {
            $query->where('name', 'LIKE', '%BD%')
                  ->orWhere('name', 'LIKE', '%GD%')
                  ->orWhere('name', 'LIKE', '%XD%');
        });

    // Apply filters
    if ($filterTournament !== 'all') {
        $matchesQuery->where('tournament_id', $filterTournament);
    }

    if ($filterCategory !== 'all') {
        $matchesQuery->whereHas('category', function ($query) use ($filterCategory) {
            $query->where('name', 'LIKE', "%{$filterCategory}%");
        });
    }

    if ($filterPlayer !== 'all') {
        $matchesQuery->where(function ($query) use ($filterPlayer) {
            $query->where('team1_player1_id', $filterPlayer)
                  ->orWhere('team1_player2_id', $filterPlayer)
                  ->orWhere('team2_player1_id', $filterPlayer)
                  ->orWhere('team2_player2_id', $filterPlayer);
        });
    }

    if (!empty($filterDate)) {
        $matchesQuery->whereDate('match_date', $filterDate);
    }

    if ($filterStage !== 'all') {
        $matchesQuery->where('stage', $filterStage);
    }

    // Apply Results Filter
    if ($filterResults !== 'all') {
        $matchesQuery->where(function ($query) use ($filterResults) {
            if ($filterResults === 'Team 1') {
                $query->whereRaw("
                    (set1_team1_points > set1_team2_points) +
                    (set2_team1_points > set2_team2_points) +
                    (IFNULL(set3_team1_points, 0) > IFNULL(set3_team2_points, 0))
                    >
                    (set1_team2_points > set1_team1_points) +
                    (set2_team2_points > set2_team1_points) +
                    (IFNULL(set3_team2_points, 0) > IFNULL(set3_team1_points, 0))
                ");
            } elseif ($filterResults === 'Team 2') {
                $query->whereRaw("
                    (set1_team2_points > set1_team1_points) +
                    (set2_team2_points > set2_team1_points) +
                    (IFNULL(set3_team2_points, 0) > IFNULL(set3_team1_points, 0))
                    >
                    (set1_team1_points > set1_team2_points) +
                    (set2_team1_points > set2_team2_points) +
                    (IFNULL(set3_team1_points, 0) > IFNULL(set3_team2_points, 0))
                ");
            } elseif ($filterResults === 'Draw') {
                $query->whereRaw("
                    (set1_team1_points = set1_team2_points) +
                    (set2_team1_points = set2_team2_points) +
                    (IFNULL(set3_team1_points, 0) = IFNULL(set3_team2_points, 0)) = 3
                ");
            }
        });
    }

    // Fetch matches with pagination
    $matches = $matchesQuery->orderBy('match_date', 'desc')->paginate(10);

    return view('matches.doubles.edit', compact(
        'matches', 'tournaments', 'players', 'filterTournament', 'filterCategory', 'filterPlayer', 'filterDate', 'filterStage', 'filterResults'
    ));
}


public function update(Request $request, $id)
{
    try {
        // Log incoming request
        \Log::info('🔄 Update request received', ['match_id' => $id, 'data' => $request->all()]);

        $match = Matches::findOrFail($id);

        // ✅ Correct ENUM values
        $allowedStages = ['Pre Quarter Finals', 'Quarter Finals', 'Semifinals', 'Finals'];

        // Validate input
        $validatedData = $request->validate([
            'stage' => ['nullable', 'string', function ($attribute, $value, $fail) use ($allowedStages) {
                if (!in_array($value, $allowedStages, true)) {
                    $fail("Invalid ENUM value for stage: $value");
                }
            }],
            'match_date' => 'nullable|date',
            'match_time' => 'nullable|string',
            'set1_team1_points' => 'nullable|integer',
            'set1_team2_points' => 'nullable|integer',
            'set2_team1_points' => 'nullable|integer',
            'set2_team2_points' => 'nullable|integer',
            'set3_team1_points' => 'nullable|integer',
            'set3_team2_points' => 'nullable|integer',
        ]);

        // Log validated data
        \Log::info('✅ Validated Data:', $validatedData);

        // Update match fields
        $match->update($validatedData);

        return response()->json(['success' => true, 'message' => 'Match updated successfully!']);
    } catch (\Illuminate\Database\QueryException $e) {
        \Log::error('❌ SQL Error:', ['error' => $e->getMessage()]);
        return response()->json(['success' => false, 'message' => 'Database error!', 'error' => $e->getMessage()], 500);
    } catch (\Exception $e) {
        \Log::error('❌ General Update Error:', ['error' => $e->getMessage()]);
        return response()->json(['success' => false, 'message' => 'Update failed!', 'error' => $e->getMessage()], 500);
    }
}

public function softDelete($id)
{
    $match = Matches::find($id);

    if (!$match) {
        return response()->json(['success' => false, 'message' => 'Match not found.'], 404);
    }

    $match->delete(); // Soft delete the match

    return response()->json(['success' => true, 'message' => 'Match soft deleted successfully.']);
}


public function updateMatch(Request $request, $id)
{
    $match = Matches::find($id);

    if (!$match) {
        return response()->json(['success' => false, 'message' => 'Match not found.'], 404);
    }

    // Validate input
    $validatedData = $request->validate([
        'stage' => 'nullable|string',
        'match_date' => 'nullable|date',
        'match_time' => 'nullable|string',
        'set1_team1_points' => 'nullable|integer',
        'set1_team2_points' => 'nullable|integer',
        'set2_team1_points' => 'nullable|integer',
        'set2_team2_points' => 'nullable|integer',
        'set3_team1_points' => 'nullable|integer',
        'set3_team2_points' => 'nullable|integer',
    ]);

    // Update match fields
    $match->update($validatedData);

    // Recalculate winner
    $team1_sets = ($match->set1_team1_points > $match->set1_team2_points) +
                  ($match->set2_team1_points > $match->set2_team2_points) +
                  ($match->set3_team1_points > $match->set3_team2_points);
    $team2_sets = ($match->set1_team2_points > $match->set1_team1_points) +
                  ($match->set2_team2_points > $match->set2_team1_points) +
                  ($match->set3_team2_points > $match->set3_team1_points);

    $match->winner = $team1_sets > $team2_sets ? 'Team 1' : ($team2_sets > $team1_sets ? 'Team 2' : 'Draw');
    $match->save();

    return response()->json(['success' => true, 'winner' => $match->winner]);
}


}
