<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Matches;
use App\Models\Tournament;
use App\Models\Category;
use App\Models\Player;

class MatchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Index - View all matches (Singles, Doubles, Mixed, Teams)
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user->isAdmin();
        
        $matchType = $request->input('match_type', 'all');
        $query = Matches::with(['tournament', 'category', 'player1', 'player2', 'team1Player1', 'team1Player2', 'team2Player1', 'team2Player2'])
                        ->whereNull('deleted_at');

        // Apply filters
        if ($matchType !== 'all') {
            $query->where('match_type', $matchType);
        }
        if ($request->filled('filter_tournament')) {
            $query->where('tournament_id', $request->filter_tournament);
        }
        if ($request->filled('filter_player')) {
            $query->where(function ($q) use ($request) {
                $q->where('player1_id', $request->filter_player)
                  ->orWhere('player2_id', $request->filter_player)
                  ->orWhere('team1_player1_id', $request->filter_player)
                  ->orWhere('team1_player2_id', $request->filter_player)
                  ->orWhere('team2_player1_id', $request->filter_player)
                  ->orWhere('team2_player2_id', $request->filter_player);
            });
        }
        
        if (!$isAdmin) {
            $query->where(function ($q) use ($user) {
                $q->where('created_by', $user->id)
                  ->orWhereHas('tournament.moderators', fn($q2) => $q2->where('user_id', $user->id));
            });
        }

        $matches = $query->orderBy('match_date', 'desc')->paginate(10);
        return view('matches.index', compact('matches'));
    }

    /**
     * Create Match (Form View)
     */
    public function create()
    {
        $user = Auth::user();
        $tournaments = Tournament::where('created_by', $user->id)
            ->orWhereHas('moderators', fn($q) => $q->where('user_id', $user->id))
            ->get();

        return view('matches.create', compact('tournaments'));
    }

    /**
     * Store Match (Singles, Doubles, Mixed, Teams)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id'   => 'required|exists:categories,id',
            'stage'         => 'required|string',
            'match_date'    => 'required|date',
            'match_time'    => 'required',
            'match_type'    => 'required|string',
            'set1_team1_points' => 'nullable|integer',
            'set1_team2_points' => 'nullable|integer',
            'set2_team1_points' => 'nullable|integer',
            'set2_team2_points' => 'nullable|integer',
            'set3_team1_points' => 'nullable|integer',
            'set3_team2_points' => 'nullable|integer',
        ]);

        $match = new Matches($validated);
        $match->created_by = Auth::id();
        $match->save();

        return redirect()->route('matches.index')->with('success', 'Match added successfully.');
    }

    /**
     * Edit Match
     */
    public function edit($id)
    {
        $match = Matches::findOrFail($id);
        return view('matches.edit', compact('match'));
    }

    /**
     * Update Match
     */
    public function update(Request $request, $id)
    {
        $match = Matches::findOrFail($id);

        if (!Auth::user()->canModerateMatch($match)) {
            abort(403, 'You do not have permission to update this match.');
        }

        $validated = $request->validate([
            'stage'         => 'nullable|string',
            'match_date'    => 'nullable|date',
            'match_time'    => 'nullable|string',
            'set1_team1_points' => 'nullable|integer',
            'set1_team2_points' => 'nullable|integer',
            'set2_team1_points' => 'nullable|integer',
            'set2_team2_points' => 'nullable|integer',
            'set3_team1_points' => 'nullable|integer',
            'set3_team2_points' => 'nullable|integer',
        ]);

        $match->update($validated);

        return redirect()->route('matches.index')->with('success', 'Match updated successfully.');
    }

    /**
     * Delete Match
     */
    public function delete($id)
    {
        $match = Matches::findOrFail($id);

        if (!Auth::user()->canModerateMatch($match)) {
            abort(403, 'You do not have permission to delete this match.');
        }

        $match->delete();
        return redirect()->route('matches.index')->with('success', 'Match deleted successfully.');
    }

    
}
