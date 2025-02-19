<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
     * Show a list of singles matches (View-Only, No Edit/Delete)
     */
    public function indexSinglesViewOnly(Request $request)
    {
        $matches = Matches::with(['tournament', 'category', 'player1', 'player2'])
            ->whereNull('deleted_at')
            ->orderBy('id')
            ->paginate(10); // ✅ Ensure pagination

        $tournaments = Tournament::all();
        $categories = Category::all();
        $players = Player::all();

        return view('matches.singles.index', compact('matches', 'tournaments', 'categories', 'players'));
    }

    /**
     * Show a list of singles matches (With Edit/Delete based on permissions)
     */
    public function indexSinglesWithEdit(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user->is_admin;

        $matches = Matches::with(['tournament', 'category', 'player1', 'player2'])
            ->whereNull('deleted_at');

        if (!$isAdmin) {
            $matches->where(function ($q) use ($user) {
                $q->where('created_by', $user->id)
                    ->orWhereHas('tournament.moderators', fn ($q2) => $q2->where('user_id', $user->id));
            });
        }

        $matches = $matches->orderBy('id')->paginate(10);

        $tournaments = Tournament::all();
        $categories = Category::all();
        $players = Player::all();

        return view('matches.singles.edit', compact('matches', 'tournaments', 'categories', 'players'));
    }

    /**
     * Store a new singles match
     */
    public function storeSingles(Request $request)
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
        $match->set3_player1_points = $request->input('set3_player1_points') ?? null;
        $match->set3_player2_points = $request->input('set3_player2_points') ?? null;
        $match->created_by    = Auth::id();
        $match->save();

        return redirect()->back()->with('success', 'Match successfully added!');
    }

    /**
     * Edit a singles match
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
     * Update a singles match
     */
    public function updateSingles(Request $request, $id)
    {
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

        $match->update([
            'stage'                 => $request->input('stage'),
            'match_date'            => $request->input('match_date'),
            'match_time'            => strlen($request->input('match_time')) === 5 ? $request->input('match_time') . ':00' : $request->input('match_time'),
            'set1_player1_points'   => $request->input('set1_player1_points') ?? 0,
            'set1_player2_points'   => $request->input('set1_player2_points') ?? 0,
            'set2_player1_points'   => $request->input('set2_player1_points') ?? 0,
            'set2_player2_points'   => $request->input('set2_player2_points') ?? 0,
            'set3_player1_points'   => $request->input('set3_player1_points') ?? 0,
            'set3_player2_points'   => $request->input('set3_player2_points') ?? 0,
        ]);

        return redirect()->route('matches.singles.index')->with('success', 'Match updated successfully.');
    }

    /**
 * Insert a new singles match into the database.
 */
public function insertSinglesMatch(Request $request)
{
    $request->validate([
        'tournament_id'       => 'required|exists:tournaments,id',
        'category_id'         => 'required|exists:categories,id',
        'player1_id'          => 'required|exists:players,id|different:player2_id',
        'player2_id'          => 'required|exists:players,id',
        'stage'               => 'required|string',
        'date'                => 'required|date',
        'match_time'          => 'required',
        'set1_player1_points' => 'required|integer',
        'set1_player2_points' => 'required|integer',
        'set2_player1_points' => 'required|integer',
        'set2_player2_points' => 'required|integer',
        'set3_player1_points' => 'nullable|integer',
        'set3_player2_points' => 'nullable|integer',
    ]);

    // Ensure match time is stored in HH:MM:SS format
    $match_time = $request->input('match_time');
    if (strlen($match_time) === 5) {
        $match_time .= ':00';
    }

    $match = new Matches();
    $match->tournament_id       = $request->input('tournament_id');
    $match->category_id         = $request->input('category_id');
    $match->player1_id          = $request->input('player1_id');
    $match->player2_id          = $request->input('player2_id');
    $match->stage               = $request->input('stage');
    $match->match_date          = $request->input('date');
    $match->match_time          = $match_time;
    $match->set1_player1_points = $request->input('set1_player1_points');
    $match->set1_player2_points = $request->input('set1_player2_points');
    $match->set2_player1_points = $request->input('set2_player1_points');
    $match->set2_player2_points = $request->input('set2_player2_points');
    $match->set3_player1_points = $request->input('set3_player1_points');
    $match->set3_player2_points = $request->input('set3_player2_points');
    $match->created_by          = Auth::id();
    $match->save();

    return redirect()->route('matches.singles.create')->with('success', 'Match successfully added!');
}

}
