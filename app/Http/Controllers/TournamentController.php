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

        if ($userRole !== 'admin') {
            // Non-admin: Only fetch tournaments created by the current user.
            $query = "
                SELECT 
                    t.id AS tournament_id, 
                    t.name AS tournament_name, 
                    t.created_by,
                    GROUP_CONCAT(DISTINCT c.name ORDER BY c.name SEPARATOR ', ') AS categories,
                    GROUP_CONCAT(DISTINCT m.username ORDER BY m.username SEPARATOR ', ') AS moderators
                FROM tournaments t
                LEFT JOIN tournament_categories tc ON t.id = tc.tournament_id
                LEFT JOIN categories c ON tc.category_id = c.id
                LEFT JOIN tournament_moderators tm ON t.id = tm.tournament_id
                LEFT JOIN users m ON tm.user_id = m.id
                WHERE t.created_by = ?
                GROUP BY t.id, t.name
            ";
            $tournaments = DB::select($query, [$userId]);
        } else {
            // Admin: Fetch all tournaments.
            $query = "
                SELECT 
                    t.id AS tournament_id, 
                    t.name AS tournament_name, 
                    GROUP_CONCAT(DISTINCT c.name ORDER BY c.name SEPARATOR ', ') AS categories,
                    GROUP_CONCAT(DISTINCT m.username ORDER BY m.username SEPARATOR ', ') AS moderators
                FROM tournaments t
                LEFT JOIN tournament_categories tc ON t.id = tc.tournament_id
                LEFT JOIN categories c ON tc.category_id = c.id
                LEFT JOIN tournament_moderators tm ON t.id = tm.tournament_id
                LEFT JOIN users m ON tm.user_id = m.id
                GROUP BY t.id, t.name
            ";
            $tournaments = DB::select($query);
        }

        // Get all categories and users for the dropdown lists.
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

        if ($inserted) {
            return redirect()->route('tournaments.manage')->with('success', 'Tournament added successfully.');
        }
        return redirect()->route('tournaments.manage')->with('error', 'Error adding tournament.');
    }

    // Show the form for editing a tournament (all editable columns)
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
            ->pluck('category_id')->toArray();

        $assignedModerators = DB::table('tournament_moderators')
            ->where('tournament_id', $id)
            ->pluck('user_id')->toArray();

        // Retrieve all available categories and moderators
        $allCategories = DB::table('categories')->get();
        $allModerators = DB::table('users')->select('id', 'username')->orderBy('username')->get();

        return view('tournaments.edit', compact(
            'tournament',
            'assignedCategories',
            'assignedModerators',
            'allCategories',
            'allModerators'
        ));
    }

    // Update the tournament (name, category assignments, and moderator assignments)
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'categories'  => 'array',
            'moderators'  => 'array',
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
        // Remove existing assignments
        DB::table('tournament_categories')->where('tournament_id', $id)->delete();
        // Insert new assignments if provided
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

        try {
            DB::table('tournaments')->where('id', $id)->delete();
            return redirect()->route('tournaments.manage')->with('success', 'Tournament deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('tournaments.manage')->with('error', 'Error deleting tournament: ' . $e->getMessage());
        }
    }

    // Existing methods for assigning categories and moderators...
    public function assignCategory(Request $request)
    {
        $request->validate([
            'tournament_id' => 'required|integer',
            'category_id'   => 'required|integer',
        ]);

        $currentUser  = Auth::user();
        $tournamentId = $request->input('tournament_id');
        $categoryId   = $request->input('category_id');

        if ($currentUser->role !== 'admin') {
            $exists = DB::table('tournaments')
                ->where('id', $tournamentId)
                ->where('created_by', $currentUser->id)
                ->exists();

            if (!$exists) {
                return redirect()->route('tournaments.manage')->with('error', 'Unauthorized access.');
            }
        }

        try {
            DB::table('tournament_categories')->updateOrInsert(
                ['tournament_id' => $tournamentId, 'category_id' => $categoryId],
                []
            );
            return redirect()->route('tournaments.manage')->with('success', 'Category assigned successfully.');
        } catch (\Exception $e) {
            return redirect()->route('tournaments.manage')->with('error', 'Error assigning category: ' . $e->getMessage());
        }
    }

    public function assignModerator(Request $request)
    {
        $request->validate([
            'tournament_id' => 'required|integer',
            'moderator_id'  => 'required|integer',
        ]);

        $currentUser  = Auth::user();
        $tournamentId = $request->input('tournament_id');
        $moderatorId  = $request->input('moderator_id');

        if ($currentUser->role !== 'admin') {
            $exists = DB::table('tournaments')
                ->where('id', $tournamentId)
                ->where('created_by', $currentUser->id)
                ->exists();

            if (!$exists) {
                return redirect()->route('tournaments.manage')->with('error', 'Unauthorized access.');
            }
        }

        try {
            DB::table('tournament_moderators')->updateOrInsert(
                ['tournament_id' => $tournamentId, 'user_id' => $moderatorId],
                []
            );
            return redirect()->route('tournaments.manage')->with('success', 'Moderator assigned successfully.');
        } catch (\Exception $e) {
            return redirect()->route('tournaments.manage')->with('error', 'Error assigning moderator: ' . $e->getMessage());
        }
    }

    public function removeModerator(Request $request)
    {
        $request->validate([
            'tournament_id' => 'required|integer',
            'moderator_id'  => 'required|integer',
        ]);

        $currentUser  = Auth::user();
        $tournamentId = $request->input('tournament_id');
        $moderatorId  = $request->input('moderator_id');

        if ($currentUser->role !== 'admin') {
            $exists = DB::table('tournaments')
                ->where('id', $tournamentId)
                ->where('created_by', $currentUser->id)
                ->exists();

            if (!$exists) {
                return redirect()->route('tournaments.manage')->with('error', 'Unauthorized access.');
            }
        }

        try {
            DB::table('tournament_moderators')
                ->where('tournament_id', $tournamentId)
                ->where('user_id', $moderatorId)
                ->delete();
            return redirect()->route('tournaments.manage')->with('success', 'Moderator removed successfully.');
        } catch (\Exception $e) {
            return redirect()->route('tournaments.manage')->with('error', 'Error removing moderator: ' . $e->getMessage());
        }
    }
}
