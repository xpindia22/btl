<?php

namespace App\Http\Controllers;

use App\Models\Matches;
use App\Models\Tournament;
use App\Models\Category;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\MatchNotificationService;

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

        // Get all tournaments (for the dropdown, presumably).
        $championships = Tournament::all();

        // Check if a tournament is "locked" in session.
        $lockedTournamentId = session('locked_tournament');
        $lockedTournament   = $lockedTournamentId ? Tournament::find($lockedTournamentId) : null;

        // Only show doubles categories (BD, GD, XD) for the locked tournament.
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
    // 2) Store a New Doubles Match
    // ----------------------------------------
    public function storeDoubles(Request $request)
    {
        Log::info('StoreDoubles function started.');

        if (!session('locked_tournament')) {
            Log::error('Tournament not locked.');
            return redirect()->back()->withErrors('You must lock a tournament before adding a match.');
        }

        $lockedTournamentId = session('locked_tournament');
        Log::info('Tournament locked: ' . $lockedTournamentId);

        // Validate category
        $category = Category::find($request->input('category_id'));
        if (!$category) {
            Log::error('Category not found. ID: ' . $request->input('category_id'));
            return redirect()->back()->withErrors('Category not found.');
        }

        Log::info('Category selected: ' . $category->name);

        // Determine if it's mixed doubles
        $catName = strtoupper($category->name);
        $isMixed = (strpos($catName, 'XD') !== false) || (strpos($catName, 'MIXED') !== false);

        // Validation rules
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
            $match->tournament_id       = $lockedTournamentId;
            $match->category_id         = $validated['category_id'];
            $match->stage               = $validated['stage'];
            $match->match_date          = $validated['date'];
            $match->match_time          = $validated['match_time'];
            $match->set1_team1_points   = $validated['set1_team1_points'];
            $match->set1_team2_points   = $validated['set1_team2_points'];
            $match->set2_team1_points   = $validated['set2_team1_points'];
            $match->set2_team2_points   = $validated['set2_team2_points'];
            $match->set3_team1_points   = $validated['set3_team1_points'] ?? null;
            $match->set3_team2_points   = $validated['set3_team2_points'] ?? null;
            $match->created_by          = Auth::id();

            // Assign players depending on if it's Mixed Doubles or not
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

            // Load necessary relationships for email content
            $match->load([
                'tournament', 
                'category', 
                'team1Player1', 
                'team1Player2', 
                'team2Player1', 
                'team2Player2', 
                'createdBy'
            ]);

            // NEW CODE for moderators
            // Fetch all moderators assigned to the tournament
            $moderators = $match->tournament
                ->moderators()
                ->pluck('email')
                ->toArray();

            // Prepare email recipients
            $creatorEmail = $match->createdBy->email;
            $playerEmails = collect([
                $match->team1Player1,
                $match->team1Player2,
                $match->team2Player1,
                $match->team2Player2,
            ])->filter()->pluck('email')->toArray();

            $adminEmail = 'xpindia@gmail.com';

            // Merge them all
            $recipients = array_unique(array_merge(
                [$creatorEmail],
                $playerEmails,
                $moderators,  // add moderators here
                [$adminEmail]
            ));

            Log::info('Sending doubles match email to recipients:', $recipients);

            \Mail::to($recipients)->send(new \App\Mail\MatchCreatedMail($match));
            Log::info('Doubles match email sent.');

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
        preg_match('/(\d+)/', $catName, $matches);
        $ageLimit = isset($matches[1]) ? (int) $matches[1] : null;

        if (strpos($catName, 'SENIOR') !== false && $ageLimit) {
            $playersQuery->where('age', '>=', $ageLimit);
        } elseif (preg_match('/U(\d+)/', $catName, $matches) && isset($matches[1])) {
            $playersQuery->where('age', '<', (int)$matches[1]);
        }

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

    // ----------------------------------------
    // 6) Index (Read-Only View)
    // ----------------------------------------
    public function index(Request $request)
    {
        $tournaments = Tournament::all();
        $players = Player::all();

        // Filters
        $filterTournament = $request->input('filter_tournament', 'all');
        $filterCategory   = $request->input('filter_category', 'all');
        $filterPlayer     = $request->input('filter_player', 'all');
        $filterDate       = $request->input('filter_date', '');
        $filterStage      = $request->input('filter_stage', 'all');
        $filterResults    = $request->input('filter_results', 'all');

        $matchesQuery = Matches::with([
            'tournament', 'category',
            'team1Player1', 'team1Player2',
            'team2Player1', 'team2Player2'
        ])->whereNull('deleted_at');

        // Restrict to doubles categories (only those containing BD, GD, or XD)
        $matchesQuery->whereHas('category', function ($query) {
            $query->where('name', 'LIKE', '%BD%')
                  ->orWhere('name', 'LIKE', '%GD%')
                  ->orWhere('name', 'LIKE', '%XD%');
        });

        // Apply additional filters
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

        // Results filter
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

        $matches = $matchesQuery->orderBy('id', 'desc')->paginate(5);

        return view('matches.doubles.index', compact(
            'matches', 'tournaments', 'players',
            'filterTournament', 'filterCategory', 'filterPlayer',
            'filterDate', 'filterStage', 'filterResults'
        ));
    }

    // ----------------------------------------
    // 7) indexWithEdit (Inline Edit View)
    // ----------------------------------------
    public function indexWithEdit(Request $request)
    {
        $tournaments = Tournament::all();
        $players = Player::all();

        $filterTournament = $request->input('filter_tournament', 'all');
        $filterCategory   = $request->input('filter_category', 'all');
        $filterPlayer     = $request->input('filter_player', 'all');
        $filterDate       = $request->input('filter_date', '');
        $filterStage      = $request->input('filter_stage', 'all');
        $filterResults    = $request->input('filter_results', 'all');

        $matchesQuery = Matches::with([
            'tournament', 'category',
            'team1Player1', 'team1Player2',
            'team2Player1', 'team2Player2'
        ])->whereNull('deleted_at');

        // Restrict to doubles categories
        $matchesQuery->whereHas('category', function ($query) {
            $query->where('name', 'LIKE', '%BD%')
                  ->orWhere('name', 'LIKE', '%GD%')
                  ->orWhere('name', 'LIKE', '%XD%');
        });

        // Apply additional filters
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

        $matches = $matchesQuery->orderBy('id', 'desc')->paginate(5);

        return view('matches.doubles.edit', compact(
            'matches', 'tournaments', 'players',
            'filterTournament', 'filterCategory', 'filterPlayer',
            'filterDate', 'filterStage', 'filterResults'
        ));
    }

    // ----------------------------------------
    // 8) Update (PUT)
    // ----------------------------------------
    public function update(Request $request, $matchId)
    {
        Log::info("Doubles update called for match ID: " . $matchId);
        
        $data = $request->validate([
            'stage'              => 'required|string',
            'match_date'         => 'required|date',
            'match_time'         => 'required',
            'set1_team1_points'  => 'nullable|numeric',
            'set1_team2_points'  => 'nullable|numeric',
            'set2_team1_points'  => 'nullable|numeric',
            'set2_team2_points'  => 'nullable|numeric',
            'set3_team1_points'  => 'nullable|numeric',
            'set3_team2_points'  => 'nullable|numeric',
            'moderator'          => 'nullable|string',
            'creator'            => 'nullable|string',
        ]);

        Log::info("Data received:", $data);

        $match = Matches::findOrFail($matchId);
        Log::info("Match before update:", $match->toArray());

        $match->update($data);
        $updatedMatch = $match->fresh();

        Log::info("Match after update:", $updatedMatch->toArray());

        // NEW CODE for update email
        try {
            // Load relationships
            $updatedMatch->load([
                'tournament',
                'category',
                'team1Player1',
                'team1Player2',
                'team2Player1',
                'team2Player2',
                'createdBy'
            ]);

            // Grab moderator emails
            $moderators = $updatedMatch->tournament
                ->moderators()
                ->pluck('email')
                ->toArray();

            $creatorEmail = $updatedMatch->createdBy->email;
            $playerEmails = collect([
                $updatedMatch->team1Player1,
                $updatedMatch->team1Player2,
                $updatedMatch->team2Player1,
                $updatedMatch->team2Player2,
            ])->filter()->pluck('email')->toArray();

            $adminEmail = 'xpindia@gmail.com';

            $updateRecipients = array_unique(array_merge(
                [$creatorEmail],
                $playerEmails,
                $moderators,
                [$adminEmail]
            ));

            Log::info("Sending match update email to:", $updateRecipients);

            // You can create a new Mailable for update if desired (e.g. MatchUpdatedMail)
            \Mail::to($updateRecipients)->send(new \App\Mail\MatchCreatedMail($updatedMatch));

            Log::info("📩 Match update email sent: Match ID {$updatedMatch->id}");
        } catch (\Exception $e) {
            Log::error("❌ Failed to send match update notification: " . $e->getMessage());
        }
        
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Match updated successfully.',
                'match'   => $updatedMatch
            ]);
        }
        
        return redirect()->route('matches.doubles.edit')->with('success', 'Match updated successfully.');
    }

    // ----------------------------------------
    // 9) Soft Delete (DELETE)
    // ----------------------------------------
    public function softDelete($id)
    {
        $match = Matches::find($id);
        if (!$match) {
            return redirect()->back()->withErrors('Match not found.');
        }

        $match->delete(); // Soft delete the match

        return redirect()
            ->route('matches.doubles.edit')
            ->with('success', 'Match soft deleted successfully!');
    }

    public function show($id)
    {
        $match = \App\Models\Matches::find($id);

        if (!$match) {
            abort(404, 'Match not found');
        }

        return view('matches.doubles.show', compact('match'));
    }
}
