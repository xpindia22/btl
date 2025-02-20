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
    public function createDoubles(Request $request)
    {
        $user = Auth::user();

        // Get available championships (tournaments)
        $championships = Tournament::all();

        // Get tournaments accessible by the user (for locking)
        $lockedTournamentId = session('locked_tournament');
        $lockedTournament   = $lockedTournamentId ? Tournament::find($lockedTournamentId) : null;

        $players = Player::all();

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

        return view('matches.doubles.create', compact('championships', 'lockedTournament', 'players', 'categories'));
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
            $rules['team1_male']   = 'required';
            $rules['team1_female'] = 'required';
            $rules['team2_male']   = 'required';
            $rules['team2_female'] = 'required';
        } else {
            $rules['team1_player1'] = 'required|different:team1_player2';
            $rules['team1_player2'] = 'required';
            $rules['team2_player1'] = 'required|different:team2_player2';
            $rules['team2_player2'] = 'required';
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

        return response()->json($playersQuery->get());
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
}
