<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tournament;
use App\Models\Category;
use App\Models\Matches_gd; // Your Girls Doubles model
use Carbon\Carbon;
use DB;

class DoublesGirlsMatchController extends Controller
{
    /**
     * Helper method to format the match time.
     * If the input is in "HH:MM" format (one colon), it appends ":00" to make it "HH:MM:SS".
     */
    protected function formatMatchTime($timeInput)
    {
        if (substr_count($timeInput, ':') === 1) {
            return $timeInput . ':00';
        }
        return $timeInput;
    }

    /**
     * Show the form to insert a new Girls Doubles match.
     */
    public function create(Request $request)
    {
        $user = Auth::user();

        // Get tournaments created by or moderated by the user.
        $tournaments = Tournament::where('created_by', $user->id)
            ->orWhereHas('moderators', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->get();

        // Check if a tournament is locked (from session)
        $lockedTournamentId = session('locked_tournament');
        if ($lockedTournamentId) {
            // For Girls Doubles, filter categories with 'GD' in their name.
            $categories = Category::join('tournament_categories', 'categories.id', '=', 'tournament_categories.category_id')
                ->where('tournament_categories.tournament_id', $lockedTournamentId)
                ->where('categories.name', 'like', '%GD%')
                ->select('categories.*')
                ->get();
        } else {
            $categories = collect();
        }

        return view('matches.doubles_girls.create', compact('tournaments', 'categories', 'lockedTournamentId'));
    }

    /**
     * Handle the submission of the new Girls Doubles match form.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Handle tournament locking/unlocking logic.
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

        // Validate required fields.
        $validated = $request->validate([
            'tournament_id' => 'required|integer',
            'category_id'   => 'required|integer',
            'stage'         => 'required|string',
            'date'          => 'required|date_format:Y-m-d',
            'time'          => 'required',
        ]);

        $match_date = Carbon::createFromFormat('Y-m-d', $validated['date'])->format('Y-m-d');
        $match_time = $this->formatMatchTime($request->input('time'));

        $match = new Matches_gd();
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

    /**
     * Display the list of Girls Doubles matches (for editing).
     * Here we use a query builder with joins to mimic the original PHP code.
     */
    public function index()
    {
        $user = Auth::user();

        $matches = DB::table('matches as m')
            ->join('tournaments as t', 'm.tournament_id', '=', 't.id')
            ->join('categories as c', 'm.category_id', '=', 'c.id')
            ->leftJoin('players as p1', 'm.team1_player1_id', '=', 'p1.id')
            ->leftJoin('players as p2', 'm.team1_player2_id', '=', 'p2.id')
            ->leftJoin('players as p3', 'm.team2_player1_id', '=', 'p3.id')
            ->leftJoin('players as p4', 'm.team2_player2_id', '=', 'p4.id')
            ->select(
                'm.id as match_id',
                't.name as tournament_name',
                'c.name as category_name',
                'p1.name as team1_player1_name',
                'p2.name as team1_player2_name',
                'p3.name as team2_player1_name',
                'p4.name as team2_player2_name',
                'm.stage',
                'm.match_date',
                'm.match_time',
                'm.set1_team1_points',
                'm.set1_team2_points',
                'm.set2_team1_points',
                'm.set2_team2_points',
                'm.set3_team1_points',
                'm.set3_team2_points',
                'm.created_by'
            )
            ->where('c.type', 'doubles')
            ->where('c.sex', 'F')
            ->where(function ($q) use ($user) {
                $q->where('m.created_by', $user->id)
                  ->orWhereExists(function ($query) use ($user) {
                      $query->select(DB::raw(1))
                            ->from('tournament_moderators as tm')
                            ->join('tournaments as t', 'tm.tournament_id', '=', 't.id')
                            ->whereRaw('t.id = m.tournament_id')
                            ->where('tm.user_id', $user->id);
                  });
            })
            ->orderBy('m.id')
            ->get();

        $stages = ['Pre Quarter Finals','Quarter Finals','Semifinals','Finals','Preliminary'];
        return view('matches.doubles_girls.edit_results', compact('matches', 'stages'));
    }

    // Alias edit() to index()
    public function edit()
    {
        return $this->index();
    }

    /**
     * Update a Girls Doubles match.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'stage'      => 'required|string',
            'match_date' => 'required|date_format:Y-m-d',
            'match_time' => 'required',
        ]);

        $match_time = $this->formatMatchTime($request->input('match_time'));

        $updated = DB::table('matches')
            ->where('id', $id)
            ->where(function ($q) use ($user) {
                $q->where('created_by', $user->id)
                  ->orWhereExists(function ($query) use ($user) {
                      $query->select(DB::raw(1))
                            ->from('tournament_moderators as tm')
                            ->join('tournaments as t', 'tm.tournament_id', '=', 't.id')
                            ->whereRaw('t.id = matches.tournament_id')
                            ->where('tm.user_id', $user->id);
                  });
            })
            ->update([
                'stage' => $validated['stage'],
                'match_date' => $validated['match_date'],
                'match_time' => $match_time,
                'set1_team1_points' => $request->input('set1_team1_points', 0),
                'set1_team2_points' => $request->input('set1_team2_points', 0),
                'set2_team1_points' => $request->input('set2_team1_points', 0),
                'set2_team2_points' => $request->input('set2_team2_points', 0),
                'set3_team1_points' => $request->input('set3_team1_points', 0),
                'set3_team2_points' => $request->input('set3_team2_points', 0)
            ]);

        if ($updated) {
            return redirect()->route('results.girls_doubles')->with('message', 'Match edited successfully!');
        } else {
            return redirect()->route('results.girls_doubles')->with('message', 'No changes were made.');
        }
    }

    /**
     * Delete a Girls Doubles match.
     */
    public function destroy($id)
    {
        $user = Auth::user();

        $deleted = DB::table('matches')
            ->where('id', $id)
            ->where(function ($q) use ($user) {
                $q->where('created_by', $user->id)
                  ->orWhereExists(function ($query) use ($user) {
                      $query->select(DB::raw(1))
                            ->from('tournament_moderators as tm')
                            ->join('tournaments as t', 'tm.tournament_id', '=', 't.id')
                            ->whereRaw('t.id = matches.tournament_id')
                            ->where('tm.user_id', $user->id);
                  });
            })
            ->delete();

        if ($deleted) {
            return redirect()->route('results.girls_doubles')->with('message', 'Match deleted successfully!');
        } else {
            return redirect()->route('results.girls_doubles')->with('message', 'Error deleting match.');
        }
    }
}
