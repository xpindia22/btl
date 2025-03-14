<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Mail\TournamentNotification;
use Illuminate\Support\Facades\Mail;

class TournamentController extends Controller
{
    // Display the tournament management page
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
            ->groupBy('tournaments.id', 'tournaments.name', 'users.username')
            ->orderBy('tournaments.id', 'desc'); // Sorting in descending order

        if ($userRole !== 'admin') {
            $tournamentsQuery->where('tournaments.created_by', $userId);
        }

        $tournaments = $tournamentsQuery->paginate(10);

        return view('tournaments.index', compact('tournaments'));
    }

    // Show the form for creating a new tournament
    public function create()
{
    $categories = DB::table('categories')->get(); // ✅ Fetch all categories
    $moderators = DB::table('users')->select('id', 'username')->orderBy('username')->get(); // ✅ Fetch all moderators
    return view('tournaments.create', compact('categories', 'moderators')); // ✅ Pass both to the view
}


    // Store a new tournament
    public function store(Request $request)
{
    $request->validate([
        'tournament_name' => 'required|string|max:255|unique:tournaments,name',
        'categories' => 'array',
        'moderators' => 'array',
    ]);

    $tournamentId = DB::table('tournaments')->insertGetId([
        'name'       => $request->tournament_name,
        'created_by' => Auth::id(),
    ]);

    foreach ($request->input('categories', []) as $categoryId) {
        DB::table('tournament_categories')->insert([
            'tournament_id' => $tournamentId,
            'category_id'   => $categoryId,
        ]);
    }

    foreach ($request->input('moderators', []) as $moderatorId) {
        DB::table('tournament_moderators')->insert([
            'tournament_id' => $tournamentId,
            'user_id'       => $moderatorId,
        ]);
    }

    $tournament = (object) ['id' => $tournamentId, 'name' => $request->tournament_name];
    $initiator = Auth::user()->username;
    $adminEmail = "xpindia@gmail.com";
    $initiatorEmail = Auth::user()->email;

    Mail::to([$adminEmail, $initiatorEmail])
        ->send(new TournamentNotification($tournament, 'created', $initiator));

    return redirect()->route('tournaments.index')->with('success', 'Tournament created successfully.');
}

public function assignCategories(Request $request, $id)
{
    DB::table('tournament_categories')->where('tournament_id', $id)->delete();

    foreach ($request->input('categories', []) as $categoryId) {
        DB::table('tournament_categories')->insert([
            'tournament_id' => $id,
            'category_id'   => $categoryId,
        ]);
    }

    return redirect()->route('tournaments.index')->with('success', 'Categories assigned successfully.');
}

    // Show the inline edit form
    public function edit()
{
    $tournaments = DB::table('tournaments')
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
        ->groupBy('tournaments.id', 'tournaments.name', 'users.username')
        ->orderBy('tournaments.id', 'desc')
        ->get();

    $allModerators = DB::table('users')->select('id', 'username')->orderBy('username')->get();
    $allCategories = DB::table('categories')->get();

    return view('tournaments.edit', compact('tournaments', 'allModerators', 'allCategories'));
}


    // Update tournament details
    public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'categories' => 'array',
        'moderators' => 'array',
    ]);

    DB::table('tournaments')->where('id', $id)->update(['name' => $request->input('name')]);

    DB::table('tournament_categories')->where('tournament_id', $id)->delete();
    foreach ($request->input('categories', []) as $categoryId) {
        DB::table('tournament_categories')->insert([
            'tournament_id' => $id,
            'category_id'   => $categoryId,
        ]);
    }

    DB::table('tournament_moderators')->where('tournament_id', $id)->delete();
    foreach ($request->input('moderators', []) as $moderatorId) {
        DB::table('tournament_moderators')->insert([
            'tournament_id' => $id,
            'user_id'       => $moderatorId,
        ]);
    }

    $tournament = (object) ['id' => $id, 'name' => $request->input('name')];
    $initiator = Auth::user()->username;
    $adminEmail = "xpindia@gmail.com";
    $initiatorEmail = Auth::user()->email;

    Mail::to([$adminEmail, $initiatorEmail])
        ->send(new TournamentNotification($tournament, 'updated', $initiator));

    return redirect()->route('tournaments.edit')->with('success', 'Tournament updated successfully.');
}

    // Delete a tournament
    public function destroy($id)
{
    $tournament = DB::table('tournaments')->where('id', $id)->first();
    if (!$tournament) {
        return redirect()->route('tournaments.edit')->with('error', 'Tournament not found.');
    }

    DB::table('tournaments')->where('id', $id)->delete();

    $tournamentData = (object) ['id' => $id, 'name' => $tournament->name];
    $initiator = Auth::user()->username;
    $adminEmail = "xpindia@gmail.com";
    $initiatorEmail = Auth::user()->email;

    Mail::to([$adminEmail, $initiatorEmail])
        ->send(new TournamentNotification($tournamentData, 'deleted', $initiator));

    return redirect()->route('tournaments.edit')->with('success', 'Tournament deleted successfully.');
}

}
