<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TournamentController extends Controller
{
    // Display the tournament management page (list tournaments)
    public function index()
    {
        $currentUser = Auth::user();
        $userId      = $currentUser->id;
        $userRole    = $currentUser->role;

        $tournamentsQuery = DB::table('tournaments')
            ->select(
                'tournaments.id as tournament_id',
                'tournaments.name as tournament_name',
                'users.username as created_by',
                DB::raw("GROUP_CONCAT(DISTINCT categories.name ORDER BY categories.name SEPARATOR ', ') as categories"),
                DB::raw("GROUP_CONCAT(DISTINCT moderators.username ORDER BY moderators.username SEPARATOR ', ') as moderators")
            )
            ->leftJoin('users', 'tournaments.created_by', '=', 'users.id')
            ->leftJoin('tournament_categories', 'tournaments.id', '=', 'tournament_categories.tournament_id')
            ->leftJoin('categories', 'tournament_categories.category_id', '=', 'categories.id')
            ->leftJoin('tournament_moderators', 'tournaments.id', '=', 'tournament_moderators.tournament_id')
            ->leftJoin('users as moderators', 'tournament_moderators.user_id', '=', 'moderators.id')
            ->groupBy('tournaments.id', 'tournaments.name', 'users.username');

        // If the user is not an admin, filter only their tournaments
        if ($userRole !== 'admin') {
            $tournamentsQuery->where('tournaments.created_by', $userId);
        }

        // Paginate results
        $tournaments = $tournamentsQuery->paginate(10); // Show 10 per page

        // Fetch dropdown data
        $categories = DB::table('categories')->get();
        $users      = DB::table('users')->select('id', 'username')->orderBy('username')->get();

        return view('tournaments.manage', compact('tournaments', 'categories', 'users'));
    }

    // Show the form for creating a new tournament
    public function create()
    {
        return view('tournaments.create');
    }

    // Add a new tournament
    public function storeTournament(Request $request)
    {
        $request->validate([
            'tournament_name' => 'required|string|max:255',
        ]);

        $currentUser    = Auth::user();
        $tournamentName = $request->input('tournament_name');

        $inserted = DB::table('tournaments')->insert([
            'name'       => $tournamentName,
            'created_by' => $currentUser->id,
        ]);

        return $inserted
            ? redirect()->route('tournaments.manage')->with('success', 'Tournament added successfully.')
            : redirect()->route('tournaments.manage')->with('error', 'Error adding tournament.');
    }

    // Show the form for editing a tournament
    public function edit($id)
{
    $currentUser = Auth::user();
    $tournament = DB::table('tournaments')->where('id', $id)->first();

    if (!$tournament) {
        return redirect()->route('tournaments.manage')->with('error', 'Tournament not found.');
    }

    // Only allow admins or the tournament owner to edit
    if ($currentUser->role !== 'admin' && $tournament->created_by != $currentUser->id) {
        return redirect()->route('tournaments.manage')->with('error', 'Unauthorized access.');
    }

    // Retrieve current category and moderator assignments
    $assignedCategories = DB::table('tournament_categories')
        ->where('tournament_id', $id)
        ->pluck('category_id')
        ->toArray();

    $assignedModerators = DB::table('tournament_moderators')
        ->where('tournament_id', $id)
        ->pluck('user_id')
        ->toArray();

    // ✅ Fetch all available categories and moderators
    $allCategories = DB::table('categories')->get();
    $allModerators = DB::table('users')->select('id', 'username')->orderBy('username')->get();

    // ✅ Pass all required variables to the view
    return view('tournaments.edit', compact(
        'tournament',
        'assignedCategories',
        'assignedModerators',
        'allCategories',
        'allModerators'
    ));
}


    // Update tournament details
    public function update(Request $request, $id)
{
    try {
        // Validate request
        $request->validate([
            'name' => 'required|string|max:255',
            'categories' => 'array',
            'moderators' => 'array',
        ]);

        $currentUser = Auth::user();
        $tournament = DB::table('tournaments')->where('id', $id)->first();

        if (!$tournament) {
            return redirect()->route('tournaments.manage')->with('error', 'Tournament not found.');
        }

        if ($currentUser->role !== 'admin' && $tournament->created_by != $currentUser->id) {
            return redirect()->route('tournaments.manage')->with('error', 'Unauthorized access.');
        }

        // Update tournament name
        DB::table('tournaments')
            ->where('id', $id)
            ->update(['name' => $request->input('name')]);

        // Update category assignments:
        DB::table('tournament_categories')->where('tournament_id', $id)->delete();
        $categories = $request->input('categories', []);
        foreach ($categories as $catId) {
            DB::table('tournament_categories')->insert([
                'tournament_id' => $id,
                'category_id'   => $catId,
            ]);
        }

        // Update moderator assignments:
        DB::table('tournament_moderators')->where('tournament_id', $id)->delete();
        $moderators = $request->input('moderators', []);
        foreach ($moderators as $modId) {
            DB::table('tournament_moderators')->insert([
                'tournament_id' => $id,
                'user_id'       => $modId,
            ]);
        }

        return redirect()->route('tournaments.manage')->with('success', 'Tournament updated successfully.');
    } catch (\Exception $e) {
        return redirect()->route('tournaments.manage')->with('error', 'Error updating tournament: ' . $e->getMessage());
    }
}


    // Delete a tournament (only allowed for admin or tournament owner)
    public function destroy($id)
    {
        $currentUser = Auth::user();
        $tournament = DB::table('tournaments')->where('id', $id)->first();

        if (!$tournament) {
            return redirect()->route('tournaments.manage')->with('error', 'Tournament not found.');
        }

        if ($currentUser->role !== 'admin' && $tournament->created_by != $currentUser->id) {
            return redirect()->route('tournaments.manage')->with('error', 'Unauthorized access.');
        }

        $deleted = DB::table('tournaments')->where('id', $id)->delete();

        return $deleted
            ? redirect()->route('tournaments.manage')->with('success', 'Tournament deleted successfully.')
            : redirect()->route('tournaments.manage')->with('error', 'Error deleting tournament.');
    }
}
