<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tournament;
use App\Models\Category;
use App\Models\Matches_bd; // Doubles model for Boys Doubles matches
use Carbon\Carbon;

class DoublesBoysMatchController extends Controller
{
    /**
     * Helper method to format the match time.
     * If the input is in "HH:MM" format (one colon), it appends ":00" to make it "HH:MM:SS".
     * Otherwise, returns the input as-is.
     */
    protected function formatMatchTime($timeInput)
    {
        if (substr_count($timeInput, ':') === 1) {
            return $timeInput . ':00';
        }
        return $timeInput;
    }

    // Display a list of doubles matches (index)
    public function index()
    {
        $user = Auth::user();

        $matches = Matches_bd::with([
                'tournament', 
                'category', 
                'team1Player1', 
                'team1Player2', 
                'team2Player1', 
                'team2Player2'
            ])
            ->whereHas('category', function ($q) {
                $q->where('type', 'doubles')->where('sex', 'M');
            })
            ->where(function ($q) use ($user) {
                $q->where('created_by', $user->id)
                  ->orWhereHas('tournament.moderators', function ($q) use ($user) {
                      $q->where('user_id', $user->id);
                  });
            })
            ->orderBy('id')
            ->get();

        $stages = ['Pre Quarter Finals', 'Quarter Finals', 'Semifinals', 'Finals', 'Preliminary'];

        return view('matches.doubles_boys.edit_results', compact('matches', 'stages'));
    }

    // Alias for index to serve as the edit view
    public function edit()
    {
        return $this->index();
    }

    // Show the insert doubles match form
    public function create(Request $request)
    {
        $user = Auth::user();

        $tournaments = Tournament::where('created_by', $user->id)
            ->orWhereHas('moderators', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->get();

        $lockedTournamentId = session('locked_tournament');
        if ($lockedTournamentId) {
            $categories = Category::join('tournament_categories', 'categories.id', '=', 'tournament_categories.category_id')
                ->where('tournament_categories.tournament_id', $lockedTournamentId)
                ->where('categories.name', 'like', '%BD%')
                ->select('categories.*')
                ->get();
        } else {
            $categories = collect();
        }

        return view('matches.doubles_boys.create', compact('tournaments', 'categories', 'lockedTournamentId'));
    }

    // Handle storing the new doubles match
    public function store(Request $request)
    {
        $user = Auth::user();

        // Handle tournament locking/unlocking logic
        if ($request->has('lock_tournament')) {
            $lockedTournament = intval($request->input('tournament_id'));
            session(['locked_tournament' => $lockedTournament]);

            $tournament = Tournament::where('id', $lockedTournament)
                ->where(function ($query) use ($user) {
                    $query->where('created_by', $user->id)
                          ->orWhereHas('moderators', function ($q) use ($user) {
                              $q->where('user_id', $user->id);
                          });
                })->first();

            if ($tournament) {
                session(['locked_tournament_name' => $tournament->name]);
                return redirect()->back()->with('message', "Tournament locked: " . e($tournament->name));
            } else {
                session()->forget('locked_tournament');
                return redirect()->back()->with('message', "Unauthorized access to the selected tournament.");
            }
        } elseif ($request->has('unlock_tournament')) {
            session()->forget(['locked_tournament', 'locked_tournament_name']);
            return redirect()->back();
        }

        $validated = $request->validate([
            'tournament_id' => 'required|integer',
            'category_id'   => 'required|integer',
            'stage'         => 'required|string',
            'date'          => 'required|date_format:Y-m-d',
            'match_time'    => 'required',
        ]);

        $match_date = Carbon::createFromFormat('Y-m-d', $validated['date'])->format('Y-m-d');
        $match_time = $this->formatMatchTime($request->input('match_time'));

        $match = new Matches_bd();
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

    // Update a doubles match
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'stage'      => 'required|string',
            'match_date' => 'required|date_format:Y-m-d',
            'match_time' => 'required',
        ]);

        $match = Matches_bd::findOrFail($id);
        $match->stage = $validated['stage'];
        $match->match_date = $validated['match_date'];
        $match->match_time = $this->formatMatchTime($request->input('match_time'));
        $match->set1_team1_points = $request->input('set1_team1_points', 0);
        $match->set1_team2_points = $request->input('set1_team2_points', 0);
        $match->set2_team1_points = $request->input('set2_team1_points', 0);
        $match->set2_team2_points = $request->input('set2_team2_points', 0);
        $match->set3_team1_points = $request->input('set3_team1_points', 0);
        $match->set3_team2_points = $request->input('set3_team2_points', 0);

        if ($match->save()) {
            return redirect()->route('matches.doubles_boys.index')->with('message', 'Match edited successfully!');
        } else {
            return redirect()->route('matches.doubles_boys.index')->with('message', 'No changes were made.');
        }
    }

    // Delete a doubles match
    public function destroy($id)
    {
        $user = Auth::user();

        $match = Matches_bd::findOrFail($id);

        if ($match->delete()) {
            return redirect()->route('matches.doubles_boys.index')->with('message', 'Match deleted successfully!');
        } else {
            return redirect()->route('matches.doubles_boys.index')->with('message', 'Error deleting match.');
        }
    }
}
