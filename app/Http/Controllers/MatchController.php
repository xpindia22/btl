<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Matches; // Using the Matches model (plural)
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
     * Show the general match creation form (for singles).
     */
    public function create(Request $request)
    {
        $user = Auth::user();

        // Fetch tournaments where the user is the creator or a moderator.
        $tournaments = Tournament::where('created_by', $user->id)
            ->orWhereHas('moderators', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->get();

        // Retrieve locked tournament from session.
        $lockedTournamentId = $request->session()->get('locked_tournament');
        $lockedTournament = $lockedTournamentId ? Tournament::find($lockedTournamentId) : null;

        // Fetch all players for client‑side filtering.
        $players = Player::all();

        // If a tournament is locked, fetch only BS & GS categories for that tournament.
        $categories = collect();
        if ($lockedTournament) {
            $categories = Category::whereHas('tournaments', function ($q) use ($lockedTournamentId) {
                $q->where('tournament_id', $lockedTournamentId);
            })
            ->where(function ($q) {
                $q->where('name', 'like', '%BS%')
                  ->orWhere('name', 'like', '%GS%');
            })->get();
        }

        return view('matches.singles.create', compact('tournaments', 'lockedTournament', 'players', 'categories'));
    }

    /**
     * Show the singles match creation form.
     */
    public function createSingles(Request $request)
    {
        // Reuse the general create method.
        return $this->create($request);
    }

    /**
     * Lock a tournament (store in session).
     */
    public function lockTournament(Request $request)
    {
        $request->validate([
            'tournament_id' => 'required|exists:tournaments,id',
        ]);

        $tournament = Tournament::find($request->input('tournament_id'));
        $request->session()->put('locked_tournament', $tournament->id);
        $request->session()->put('locked_tournament_name', $tournament->name);

        return redirect()->back()->with('success', 'Tournament locked: ' . $tournament->name);
    }

    /**
     * Unlock the currently locked tournament.
     */
    public function unlockTournament(Request $request)
    {
        $request->session()->forget('locked_tournament');
        $request->session()->forget('locked_tournament_name');
        return redirect()->back()->with('success', 'Tournament unlocked');
    }

    /**
     * Store a new match (singles).
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id'           => 'required|exists:categories,id',
            'player1_id'            => 'required|exists:players,id|different:player2_id',
            'player2_id'            => 'required|exists:players,id',
            'stage'                 => 'required|string',
            'date'                  => 'required|date',
            'match_time'            => 'required',
            'set1_player1_points'   => 'required|integer',
            'set1_player2_points'   => 'required|integer',
            'set2_player1_points'   => 'required|integer',
            'set2_player2_points'   => 'required|integer',
            'set3_player1_points'   => 'nullable|integer',
            'set3_player2_points'   => 'nullable|integer',
        ]);

        $lockedTournament = session('locked_tournament');
        if (!$lockedTournament) {
            return redirect()->back()->withErrors('Tournament must be locked first.');
        }

        // Ensure the match time is in "HH:MM:SS" format.
        $match_time = $request->input('match_time');
        if (strlen($match_time) === 5) {
            $match_time .= ':00';
        }

        $match = new Matches();
        $match->tournament_id = $lockedTournament;
        $match->category_id   = $request->input('category_id');
        $match->player1_id    = $request->input('player1_id');
        $match->player2_id    = $request->input('player2_id');
        $match->stage         = $request->input('stage');
        $match->match_date    = $request->input('date');
        $match->match_time    = $match_time;
        $match->set1_player1_points = $request->input('set1_player1_points');
        $match->set1_player2_points = $request->input('set1_player2_points');
        $match->set2_player1_points = $request->input('set2_player1_points');
        $match->set2_player2_points = $request->input('set2_player2_points');
        $match->set3_player1_points = $request->input('set3_player1_points');
        $match->set3_player2_points = $request->input('set3_player2_points');
        $match->created_by    = Auth::id();
        $match->save();

        return redirect()->back()->with('success', 'Match successfully added!');
    }

    /**
     * Store a new singles match.
     */
    public function storeSingles(Request $request)
    {
        // Reuse the general store method.
        return $this->store($request);
    }

    /**
     * Display a general list of matches with filters.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user->is_admin;

        $tournament_id = $request->input('tournament_id');
        $category_id   = $request->input('category_id');
        $player_id     = $request->input('player_id');
        $match_date    = $request->input('match_date');
        $datetime      = $request->input('datetime');

        $matches = Matches::query()
            ->with(['tournament', 'category', 'player1', 'player2'])
            ->whereNull('deleted_at');

        if (!$isAdmin) {
            $matches->where(function ($q) use ($user) {
                $q->where('created_by', $user->id)
                  ->orWhereHas('tournament.moderators', function ($q2) use ($user) {
                      $q2->where('user_id', $user->id);
                  });
            });
        }
        if ($tournament_id) {
            $matches->where('tournament_id', $tournament_id);
        }
        if ($category_id) {
            $matches->where('category_id', $category_id);
        }
        if ($player_id) {
            $matches->where(function ($q) use ($player_id) {
                $q->where('player1_id', $player_id)
                  ->orWhere('player2_id', $player_id);
            });
        }
        if ($match_date) {
            $matches->where('match_date', $match_date);
        }
        if ($datetime) {
            $matches->where('match_time', $datetime);
        }

        $matches = $matches->orderBy('id')->get();

        $tournaments = Tournament::all();
        $categories = Category::all();
        $players = Player::all();
        $dates = Matches::select('match_date')->distinct()->orderBy('match_date')->get();
        $datetimes = Matches::select('match_time')->distinct()->orderBy('match_time')->get();

        return view('matches.singles.index', compact(
            'matches', 'tournaments', 'categories', 'players', 'dates', 'datetimes',
            'tournament_id', 'category_id', 'player_id', 'match_date', 'datetime'
        ));
    }

    /**
     * Display a list of singles matches with filters.
     * Only include matches whose category name contains "BS" or "GS",
     * and allow filtering by a drop down for boys singles or girls singles.
     */
    public function indexSingles(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user->is_admin;
        $singles_filter = $request->input('singles_filter', 'all');

        $tournament_id = $request->input('tournament_id');
        $category_id   = $request->input('category_id');
        $player_id     = $request->input('player_id');
        $match_date    = $request->input('match_date');
        $datetime      = $request->input('datetime');

        $perPage = $request->input('per_page', 10);

        $matchesQuery = Matches::query()
            ->with(['tournament', 'category', 'player1', 'player2'])
            ->whereNull('deleted_at')
            ->whereHas('category', function ($q) use ($singles_filter) {
                if ($singles_filter === 'boys') {
                    $q->where('name', 'like', '%BS%');
                } elseif ($singles_filter === 'girls') {
                    $q->where('name', 'like', '%GS%');
                } else {
                    $q->where(function ($query) {
                        $query->where('name', 'like', '%BS%')
                              ->orWhere('name', 'like', '%GS%');
                    });
                }
            });

        if (!$isAdmin) {
            $matchesQuery->where(function ($q) use ($user) {
                $q->where('created_by', $user->id)
                  ->orWhereHas('tournament.moderators', function ($q2) use ($user) {
                      $q2->where('user_id', $user->id);
                  });
            });
        }

        if ($tournament_id) {
            $matchesQuery->where('tournament_id', $tournament_id);
        }
        if ($category_id) {
            $matchesQuery->where('category_id', $category_id);
        }
        if ($player_id) {
            $matchesQuery->where(function ($q) use ($player_id) {
                $q->where('player1_id', $player_id)
                  ->orWhere('player2_id', $player_id);
            });
        }
        if ($match_date) {
            $matchesQuery->where('match_date', $match_date);
        }
        if ($datetime) {
            $matchesQuery->where('match_time', $datetime);
        }

        $matches = $matchesQuery->orderBy('id')->paginate($perPage);

        $tournaments = Tournament::all();
        $categories = Category::all();
        $players = Player::all();
        $dates = Matches::select('match_date')->distinct()->orderBy('match_date')->get();
        $datetimes = Matches::select('match_time')->distinct()->orderBy('match_time')->get();

        return view('matches.singles.index', compact(
            'matches', 'tournaments', 'categories', 'players', 'dates', 'datetimes',
            'tournament_id', 'category_id', 'player_id', 'match_date', 'datetime', 'singles_filter'
        ));
    }

    /**
     * Show the edit form for a singles match.
     */
    public function editSingles($id)
    {
        $match = Matches::with('tournament')->findOrFail($id);
        $user = Auth::user();
        $isAdmin = $user->is_admin;
        if (!$isAdmin && $match->created_by != $user->id) {
            if (!$match->tournament || !$match->tournament->moderators()->where('user_id', $user->id)->exists()) {
                abort(403, 'You do not have permission to edit this match.');
            }
        }
        $stages = ['Pre Quarter Finals', 'Quarter Finals', 'Semifinals', 'Finals', 'Preliminary'];
        return view('matches.singles.edit', compact('match', 'stages'));
    }

    /**
     * Update a singles match.
     */
    public function updateSingles(Request $request, $id)
    {
        // Convert empty strings for point fields to null.
        $pointFields = [
            'set1_player1_points',
            'set1_player2_points',
            'set2_player1_points',
            'set2_player2_points',
            'set3_player1_points',
            'set3_player2_points'
        ];
        foreach ($pointFields as $field) {
            if ($request->has($field) && trim($request->input($field)) === '') {
                $request->merge([$field => null]);
            }
        }
        $match = Matches::findOrFail($id);
        $user = Auth::user();
        $isAdmin = $user->is_admin;
        if (!$isAdmin && $match->created_by != $user->id) {
            if (!$match->tournament || !$match->tournament->moderators()->where('user_id', $user->id)->exists()) {
                abort(403, 'You do not have permission to update this match.');
            }
        }
        $request->validate([
            'stage'                 => 'required|string',
            'match_date'            => 'required|date',
            'match_time'            => 'required',
            'set1_player1_points'   => 'nullable|integer',
            'set1_player2_points'   => 'nullable|integer',
            'set2_player1_points'   => 'nullable|integer',
            'set2_player2_points'   => 'nullable|integer',
            'set3_player1_points'   => 'nullable|integer',
            'set3_player2_points'   => 'nullable|integer',
        ]);
        $stage = $request->input('stage');
        $validStages = ['Pre Quarter Finals', 'Quarter Finals', 'Semifinals', 'Finals', 'Preliminary'];
        if (!in_array($stage, $validStages)) {
            return redirect()->back()->withErrors('Invalid stage value.');
        }
        $match_time = $request->input('match_time');
        if (strlen($match_time) === 5) {
            $match_time .= ':00';
        }
        $match->stage = $stage;
        $match->match_date = $request->input('match_date');
        $match->match_time = $match_time;
        $match->set1_player1_points = $request->input('set1_player1_points') ?? 0;
        $match->set1_player2_points = $request->input('set1_player2_points') ?? 0;
        $match->set2_player1_points = $request->input('set2_player1_points') ?? 0;
        $match->set2_player2_points = $request->input('set2_player2_points') ?? 0;
        $match->set3_player1_points = $request->input('set3_player1_points') ?? 0;
        $match->set3_player2_points = $request->input('set3_player2_points') ?? 0;
        $match->save();
        return redirect()->route('matches.singles.index')->with('success', 'Match updated successfully.');
    }

    /**
     * Delete a singles match.
     */
    public function destroySingles($id)
    {
        $match = Matches::findOrFail($id);
        $user = Auth::user();
        $isAdmin = $user->is_admin;
        if (!$isAdmin && $match->created_by != $user->id) {
            if (!$match->tournament || !$match->tournament->moderators()->where('user_id', $user->id)->exists()) {
                abort(403, 'You do not have permission to delete this match.');
            }
        }
        $match->delete();
        return redirect()->route('matches.singles.index')->with('success', 'Match deleted successfully.');
    }

    // ---------------------------
    // Doubles Boys Methods
    /**
     * Display a list of doubles boys matches with filters.
     */
    public function indexDoublesBoys(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user->is_admin;
        $tournament_id = $request->input('tournament_id');
        $category_id = $request->input('category_id');
        $player_id = $request->input('player_id');
        $match_date = $request->input('match_date');
        $datetime = $request->input('datetime');

        $matches = Matches::query()
            ->with(['tournament', 'category', 'player1', 'player2'])
            ->whereNull('deleted_at')
            ->whereHas('category', function($q) {
                $q->where('name', 'like', '%Boys%');
            });

        if (!$isAdmin) {
            $matches->where(function($q) use ($user) {
                $q->where('created_by', $user->id)
                  ->orWhereHas('tournament.moderators', function($q2) use ($user) {
                      $q2->where('user_id', $user->id);
                  });
            });
        }

        if ($tournament_id) {
            $matches->where('tournament_id', $tournament_id);
        }
        if ($category_id) {
            $matches->where('category_id', $category_id);
        }
        if ($player_id) {
            $matches->where(function($q) use ($player_id) {
                $q->where('player1_id', $player_id)
                  ->orWhere('player2_id', $player_id);
            });
        }
        if ($match_date) {
            $matches->where('match_date', $match_date);
        }
        if ($datetime) {
            $matches->where('match_time', $datetime);
        }

        $matches = $matches->orderBy('id')->get();
        $tournaments = Tournament::all();
        $categories = Category::all();
        $players = Player::all();
        $dates = Matches::select('match_date')->distinct()->orderBy('match_date')->get();
        $datetimes = Matches::select('match_time')->distinct()->orderBy('match_time')->get();

        return view('matches.doubles_boys.index', compact(
            'matches', 'tournaments', 'categories', 'players', 'dates', 'datetimes',
            'tournament_id', 'category_id', 'player_id', 'match_date', 'datetime'
        ));
    }

    /**
     * Show the form for creating a new doubles boys match.
     */
    public function createDoublesBoys(Request $request)
    {
        return view('matches.doubles_boys.create');
    }

    /**
     * Show the form for editing a doubles boys match.
     */
    public function editDoublesBoys($id)
    {
        $match = Matches::findOrFail($id);
        $user = Auth::user();
        $isAdmin = $user->is_admin;

        if (
            !$isAdmin &&
            $match->created_by != $user->id &&
            (!$match->tournament || !$match->tournament->moderators()->where('user_id', $user->id)->exists())
        ) {
            abort(403, 'You do not have permission to edit this match.');
        }

        $stages = ['Pre Quarter Finals', 'Quarter Finals', 'Semifinals', 'Finals', 'Preliminary'];
        return view('matches.doubles_boys.edit', compact('match', 'stages'));
    }

    /**
     * Update a doubles boys match.
     */
    public function updateDoublesBoys(Request $request, $id)
    {
        $match = Matches::findOrFail($id);
        $user = Auth::user();
        $isAdmin = $user->is_admin;
        if (
            !$isAdmin &&
            $match->created_by != $user->id &&
            (!$match->tournament || !$match->tournament->moderators()->where('user_id', $user->id)->exists())
        ) {
            abort(403, 'You do not have permission to update this match.');
        }

        $request->validate([
            'stage'                 => 'required|string',
            'match_date'            => 'required|date',
            'match_time'            => 'required',
            'set1_player1_points'   => 'required|integer',
            'set1_player2_points'   => 'required|integer',
            'set2_player1_points'   => 'required|integer',
            'set2_player2_points'   => 'required|integer',
            'set3_player1_points'   => 'required|integer',
            'set3_player2_points'   => 'required|integer',
        ]);

        $stage = $request->input('stage');
        $validStages = ['Pre Quarter Finals', 'Quarter Finals', 'Semifinals', 'Finals', 'Preliminary'];
        if (!in_array($stage, $validStages)) {
            return redirect()->back()->withErrors('Invalid stage value.');
        }

        $match_time = $request->input('match_time');
        if (strlen($match_time) === 5) {
            $match_time .= ':00';
        }

        $match->stage = $stage;
        $match->match_date = $request->input('match_date');
        $match->match_time = $match_time;
        $match->set1_player1_points = $request->input('set1_player1_points');
        $match->set1_player2_points = $request->input('set1_player2_points');
        $match->set2_player1_points = $request->input('set2_player1_points');
        $match->set2_player2_points = $request->input('set2_player2_points');
        $match->set3_player1_points = $request->input('set3_player1_points');
        $match->set3_player2_points = $request->input('set3_player2_points');
        $match->save();

        return redirect()->route('matches.doubles_boys.index')->with('success', 'Match updated successfully.');
    }

    /**
     * Delete a doubles boys match.
     */
    public function destroyDoublesBoys($id)
    {
        $match = Matches::findOrFail($id);
        $user = Auth::user();
        $isAdmin = $user->is_admin;
        if (
            !$isAdmin &&
            $match->created_by != $user->id &&
            (!$match->tournament || !$match->tournament->moderators()->where('user_id', $user->id)->exists())
        ) {
            abort(403, 'You do not have permission to delete this match.');
        }
        $match->delete();
        return redirect()->route('matches.doubles_boys.index')->with('success', 'Match deleted successfully.');
    }

    // ---------------------------
    // You can add similar methods for Doubles Girls and Doubles Mixed if needed.
}
