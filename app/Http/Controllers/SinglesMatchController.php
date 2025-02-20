<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Matches;
use App\Models\Tournament;
use App\Models\Category;
use App\Models\Player;

class SinglesMatchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // ------------------------------------------------------------------
    // 1) View-Only: indexSingles
    // ------------------------------------------------------------------
    public function indexSingles(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user->is_admin;

        $matchesQuery = Matches::with(['tournament', 'category', 'player1', 'player2'])
            ->whereNull('deleted_at')
            ->whereHas('category', function ($query) {
                $query->where('name', 'LIKE', '%BS%')
                      ->orWhere('name', 'LIKE', '%GS%');
            })
            ->whereDoesntHave('category', function ($query) {
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

        return view('matches.singles.index', compact('matches'));
    }

    public function indexSinglesWithEdit()
    {
        $user = Auth::user();
        $isAdmin = $user->is_admin;

        $matchesQuery = Matches::with(['tournament', 'category', 'player1', 'player2'])
            ->whereNull('deleted_at')
            ->whereHas('category', function ($query) {
                $query->where('name', 'LIKE', '%BS%')
                      ->orWhere('name', 'LIKE', '%GS%');
            })
            ->whereDoesntHave('category', function ($query) {
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

        return view('matches.singles.edit', compact('matches'));
    }

    // ------------------------------------------------------------------
    // 3) Create & Store
    // ------------------------------------------------------------------
    public function createSingles(Request $request)
    {
        $user = Auth::user();

        // Tournaments accessible by the user
        $tournaments = Tournament::where('created_by', $user->id)
            ->orWhereHas('moderators', fn($q) => $q->where('user_id', $user->id))
            ->get();

        $lockedTournamentId = session('locked_tournament');
        $lockedTournament   = $lockedTournamentId ? Tournament::find($lockedTournamentId) : null;

        $players = Player::all();

        // Only show categories that belong to the locked tournament
        // AND contain 'BS' or 'GS' in the name (singles categories)
        $categories = [];
        if ($lockedTournamentId) {
            $categories = Category::whereHas('tournaments', fn($q) => $q->where('tournament_id', $lockedTournamentId))
                ->where(function($query) {
                    $query->where('name', 'LIKE', '%BS%')
                          ->orWhere('name', 'LIKE', '%GS%');
                })
                ->get();
        }

        return view('matches.singles.create', compact('tournaments', 'lockedTournament', 'players', 'categories'));
    }

    public function storeSingles(Request $request)
    {
        if (!session('locked_tournament')) {
            return redirect()->back()->withErrors('You must lock a tournament before adding a match.');
        }

        $request->validate([
            'category_id'            => 'required|exists:categories,id',
            'player1_id'             => 'required|exists:players,id|different:player2_id',
            'player2_id'             => 'required|exists:players,id',
            'stage'                  => 'required|string',
            'date'                   => 'required|date',
            'match_time'             => 'required',
            'set1_player1_points'    => 'required|integer',
            'set1_player2_points'    => 'required|integer',
            'set2_player1_points'    => 'required|integer',
            'set2_player2_points'    => 'required|integer',
            'set3_player1_points'    => 'nullable|integer',
            'set3_player2_points'    => 'nullable|integer',
        ]);
        
        Matches::create([
            'tournament_id'            => session('locked_tournament'),
            'category_id'              => $request->input('category_id'),
            'player1_id'               => $request->input('player1_id'),
            'player2_id'               => $request->input('player2_id'),
            'stage'                    => $request->input('stage'),
            'match_date'               => $request->input('date'),
            'match_time'               => $request->input('match_time'),
            'set1_player1_points'      => $request->input('set1_player1_points'),
            'set1_player2_points'      => $request->input('set1_player2_points'),
            'set2_player1_points'      => $request->input('set2_player1_points'),
            'set2_player2_points'      => $request->input('set2_player2_points'),
            'set3_player1_points'      => $request->input('set3_player1_points'),
            'set3_player2_points'      => $request->input('set3_player2_points'),
            'created_by'               => Auth::id(),
        ]);

        return redirect()->route('matches.singles.index')->with('success', 'Match successfully added!');
    }

    // ------------------------------------------------------------------
    // 4) Lock/Unlock
    // ------------------------------------------------------------------
    public function lockTournament(Request $request)
    {
        $request->validate(['tournament_id' => 'required|exists:tournaments,id']);
        $tournament = Tournament::findOrFail($request->tournament_id);

        session(['locked_tournament' => $tournament->id]);
        session(['locked_tournament_name' => $tournament->name]);

        return redirect()->back()->with('success', 'Tournament locked: ' . $tournament->name);
    }

    public function unlockTournament(Request $request)
    {
        session()->forget(['locked_tournament', 'locked_tournament_name']);
        return redirect()->back()->with('success', 'Tournament unlocked');
    }

    // ------------------------------------------------------------------
    // 5) Edit Single Match
    // ------------------------------------------------------------------
    public function editSingleMatch($id)
    {
        $match = Matches::findOrFail($id);
        // optional: permission checks

        // Return a dedicated Blade e.g. resources/views/matches/singles/edit_single.blade.php
        return view('matches.singles.edit_single', compact('match'));
    }

    // Delete a single match
    public function deleteSingleMatch($id)
    {
        $match = Matches::findOrFail($id);
        // optional: permission checks

        $match->delete();
        return redirect()->route('matches.singles.edit')->with('success', 'Match deleted successfully!');
    }

    /**
     * Update a single match (inline edit in the table).
     */
    public function updateSingle(Request $request, $id)
    {
        $match = Matches::findOrFail($id);

        // (Optional) permission checks
        $user = Auth::user();
        if (!$user->is_admin && $match->created_by != $user->id) {
            if (!$match->tournament || !$match->tournament->moderators()->where('user_id', $user->id)->exists()) {
                abort(403, 'You do not have permission to update this match.');
            }
        }

        // Validate fields
        $request->validate([
            'stage'                 => 'required|string',
            'set1_player1_points'   => 'nullable|integer',
            'set1_player2_points'   => 'nullable|integer',
            'set2_player1_points'   => 'nullable|integer',
            'set2_player2_points'   => 'nullable|integer',
            'set3_player1_points'   => 'nullable|integer',
            'set3_player2_points'   => 'nullable|integer',
        ]);

        // Update match
        $match->stage                 = $request->input('stage');
        $match->set1_player1_points   = $request->input('set1_player1_points') ?? 0;
        $match->set1_player2_points   = $request->input('set1_player2_points') ?? 0;
        $match->set2_player1_points   = $request->input('set2_player1_points') ?? 0;
        $match->set2_player2_points   = $request->input('set2_player2_points') ?? 0;
        $match->set3_player1_points   = $request->input('set3_player1_points') ?? 0;
        $match->set3_player2_points   = $request->input('set3_player2_points') ?? 0;
        $match->save();

        return redirect()->route('matches.singles.edit')->with('success', 'Match updated successfully.');
    }

    /**
     * Delete a single match (inline in the table).
     */
    public function deleteSingle($id)
    {
        $match = Matches::findOrFail($id);

        // (Optional) permission checks
        $user = Auth::user();
        if (!$user->is_admin && $match->created_by != $user->id) {
            if (!$match->tournament || !$match->tournament->moderators()->where('user_id', $user->id)->exists()) {
                abort(403, 'You do not have permission to delete this match.');
            }
        }

        $match->delete();
        return redirect()->route('matches.singles.edit')->with('success', 'Match deleted successfully.');
    }

    // ------------------------------------------------------------------
    // 6) Filtered Players (AJAX)
    // ------------------------------------------------------------------
    public function getFilteredPlayers(Request $request)
    {
        $category = Category::find($request->category_id);
        if (!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }
    
        // 1) Start building query for players
        $playersQuery = \App\Models\Player::query();
    
        // 2) Filter by sex (if NOT 'Mixed')
        //    If category->sex == 'M', only show male players. If 'F', only show female.
        //    If 'Mixed', skip this filter so that both M and F appear.
        if ($category->sex === 'M') {
            $playersQuery->where('sex', 'M');  // male players only
        } elseif ($category->sex === 'F') {
            $playersQuery->where('sex', 'F');  // female players only
        }
    
        // 3) Parse the age group
        //    e.g. "Under 16", "Over 35", "Between 20 - 35", or "Open"
        $ageGroup = $category->age_group;
    
        if (str_starts_with($ageGroup, 'Under ')) {
            // e.g. "Under 16"
            $limit = (int) str_replace('Under ', '', $ageGroup); 
            $playersQuery->where('age', '<=', $limit);
    
        } elseif (str_starts_with($ageGroup, 'Over ')) {
            // e.g. "Over 35"
            $limit = (int) str_replace('Over ', '', $ageGroup);
            $playersQuery->where('age', '>=', $limit);
    
        } elseif (str_starts_with($ageGroup, 'Between ')) {
            // e.g. "Between 20 - 35"
            // Parse out the two numbers
            $rangePart = str_replace('Between ', '', $ageGroup); // "20 - 35"
            [$lower, $upper] = explode(' - ', $rangePart);       // $lower=20, $upper=35
            $playersQuery->whereBetween('age', [(int) $lower, (int) $upper]);
    
        } elseif ($ageGroup === 'Open') {
            // "Open" means no age restriction
            // So we skip adding any age filter
        }
    
        // 4) Execute the query
        $players = $playersQuery->get();
    
        // 5) Return as JSON so your AJAX can populate the dropdown
        return response()->json($players);
    }
    
}
