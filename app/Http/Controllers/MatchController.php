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
        // Optionally add additional middleware (e.g. for non‑player verification)
    }

    /**
     * Show the general match creation form (for singles).
     */
    public function create(Request $request)
    {
        $user = Auth::user();

        // Fetch tournaments where the user is the creator or a moderator
        $tournaments = Tournament::where('created_by', $user->id)
            ->orWhereHas('moderators', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->get();

        // Retrieve locked tournament from session (if any)
        $lockedTournamentId = $request->session()->get('locked_tournament');
        $lockedTournament = $lockedTournamentId ? Tournament::find($lockedTournamentId) : null;

        // Fetch all players for client‑side filtering
        $players = Player::all();

        // If a tournament is locked, fetch only BS & GS categories for that tournament
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

        // Ensure the match time is in "HH:MM:SS" format
        $match_time = $request->input('match_time');
        if (strlen($match_time) === 5) {
            $match_time .= ':00';
        }

        // Create the match record using the Matches model
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
     * Display a list of matches with filters.
     */
    public function index(Request $request)
    {
        $user    = Auth::user();
        $isAdmin = $user->is_admin; // Assumes an is_admin flag on your User model

        // Get filters from the query string
        $tournament_id = $request->input('tournament_id');
        $category_id   = $request->input('category_id');
        $player_id     = $request->input('player_id');
        $match_date    = $request->input('match_date');
        $datetime      = $request->input('datetime');

        // Build query with optional filters using the Matches model
        $matches = Matches::query()
            ->with(['tournament', 'category', 'player1', 'player2'])
            ->whereNull('deleted_at');

        if (!$isAdmin) {
            // Only show matches created by the user or where the user is a tournament moderator
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

        // Get dropdown options for filters
        $tournaments = Tournament::all();
        $categories  = Category::all();
        $players     = Player::all();
        $dates       = Matches::select('match_date')->distinct()->orderBy('match_date')->get();
        $datetimes   = Matches::select('match_time')->distinct()->orderBy('match_time')->get();

        return view('matches.singles.index', compact(
            'matches', 'tournaments', 'categories', 'players', 'dates', 'datetimes',
            'tournament_id', 'category_id', 'player_id', 'match_date', 'datetime'
        ));
    }

    /**
     * Display a list of singles matches with filters.
     * Only include matches whose category name contains "BS" or "GS".
     */
    /**
 * Display a list of singles matches with filters.
 * Only include matches whose category name contains "BS" or "GS".
 */
public function indexSingles(Request $request)
{
    $user    = Auth::user();
    $isAdmin = $user->is_admin;

    // Get filters from the query string
    $tournament_id = $request->input('tournament_id');
    $category_id   = $request->input('category_id');
    $player_id     = $request->input('player_id');
    $match_date    = $request->input('match_date');
    $datetime      = $request->input('datetime');

    // Build query with optional filters using the Matches model,
    // and filter to only include categories with "BS" or "GS".
    $matches = Matches::query()
        ->with(['tournament', 'category', 'player1', 'player2'])
        ->whereNull('deleted_at')
        ->whereHas('category', function ($q) {
            $q->where('name', 'like', '%BS%')
              ->orWhere('name', 'like', '%GS%');
        });

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

    // Get dropdown options for filters
    $tournaments = Tournament::all();
    $categories  = Category::all();
    $players     = Player::all();
    $dates       = Matches::select('match_date')->distinct()->orderBy('match_date')->get();
    $datetimes   = Matches::select('match_time')->distinct()->orderBy('match_time')->get();

    return view('matches.singles.index', compact(
        'matches', 'tournaments', 'categories', 'players', 'dates', 'datetimes',
        'tournament_id', 'category_id', 'player_id', 'match_date', 'datetime'
    ));
}


    /**
     * Show the edit form for a singles match.
     */
    public function editSingles($id)
    {
        $match = Matches::findOrFail($id);
        $user  = Auth::user();
        $isAdmin = $user->is_admin;

        // Check permission: only the creator or a tournament moderator (or admin) can edit.
        if (
            !$isAdmin &&
            $match->created_by != $user->id &&
            !$match->tournament->moderators()->where('user_id', $user->id)->exists()
        ) {
            abort(403, 'You do not have permission to edit this match.');
        }

        $stages = ['Pre Quarter Finals', 'Quarter Finals', 'Semifinals', 'Finals', 'Preliminary'];
        return view('matches.singles.edit', compact('match', 'stages'));
    }

    /**
     * Update a singles match.
     */
    public function updateSingles(Request $request, $id)
    {
        $match = Matches::findOrFail($id);
        $user  = Auth::user();
        $isAdmin = $user->is_admin;
        if (
            !$isAdmin &&
            $match->created_by != $user->id &&
            !$match->tournament->moderators()->where('user_id', $user->id)->exists()
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

        return redirect()->route('matches.index')->with('success', 'Match updated successfully.');
    }

    /**
     * Delete a singles match.
     */
    public function destroySingles($id)
    {
        $match = Matches::findOrFail($id);
        $user  = Auth::user();
        $isAdmin = $user->is_admin;
        if (
            !$isAdmin &&
            $match->created_by != $user->id &&
            !$match->tournament->moderators()->where('user_id', $user->id)->exists()
        ) {
            abort(403, 'You do not have permission to delete this match.');
        }
        $match->delete();
        return redirect()->route('matches.index')->with('success', 'Match deleted successfully.');
    }

    // ---------------------------
    // Doubles Boys Methods
    /**
     * Display a list of doubles boys matches with filters.
     */
    public function indexDoublesBoys(Request $request)
    {
        $user    = Auth::user();
        $isAdmin = $user->is_admin;
        $tournament_id = $request->input('tournament_id');
        $category_id   = $request->input('category_id');
        $player_id     = $request->input('player_id');
        $match_date    = $request->input('match_date');
        $datetime      = $request->input('datetime');

        $matches = Matches::query()
            ->with(['tournament', 'category', 'player1', 'player2'])
            ->whereNull('deleted_at')
            ->whereHas('category', function ($q) {
                $q->where('name', 'like', '%Boys%');
            });

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
        $categories  = Category::all();
        $players     = Player::all();
        $dates       = Matches::select('match_date')->distinct()->orderBy('match_date')->get();
        $datetimes   = Matches::select('match_time')->distinct()->orderBy('match_time')->get();

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
        // You can pass any necessary data to the view as needed.
        return view('matches.doubles_boys.create');
    }

    /**
     * Show the form for editing a doubles boys match.
     */
    public function editDoublesBoys($id)
    {
        $match = Matches::findOrFail($id);
        $user  = Auth::user();
        $isAdmin = $user->is_admin;

        if (
            !$isAdmin &&
            $match->created_by != $user->id &&
            !$match->tournament->moderators()->where('user_id', $user->id)->exists()
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
        $user  = Auth::user();
        $isAdmin = $user->is_admin;
        if (
            !$isAdmin &&
            $match->created_by != $user->id &&
            !$match->tournament->moderators()->where('user_id', $user->id)->exists()
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
        $user  = Auth::user();
        $isAdmin = $user->is_admin;
        if (
            !$isAdmin &&
            $match->created_by != $user->id &&
            !$match->tournament->moderators()->where('user_id', $user->id)->exists()
        ) {
            abort(403, 'You do not have permission to delete this match.');
        }
        $match->delete();
        return redirect()->route('matches.doubles_boys.index')->with('success', 'Match deleted successfully.');
    }

    // ---------------------------
    // You can add similar methods for Doubles Girls and Doubles Mixed if needed.
}
