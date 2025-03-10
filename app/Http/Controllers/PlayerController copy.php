<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Tournament;
use App\Models\Category;




class PlayerController extends Controller
{
    public function index()
    {
        $players = Player::orderBy('uid', 'desc')->paginate(10);
        $nextUid = $this->getNextAvailableUid();
        $showRegistration = auth()->check();

        return view('players.index', compact('players', 'nextUid', 'showRegistration'));
    }

    public function showRegistrationForm()
    {
        return $this->index();
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'dob' => 'required|date',
            'sex' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        $dob = Carbon::parse($request->dob);
        $age = $dob->diffInYears(Carbon::now());
        $uid = $this->getNextAvailableUid();

        Player::create([
            'uid' => $uid,
            'name' => $request->name,
            'dob' => $dob,
            'sex' => $request->sex,
            'password' => Hash::make($request->password),
            'age' => $age,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('players.index')->with('success', 'Player registered successfully!');
    }

    public function create()
    {
        $nextUid = $this->getNextAvailableUid();
        $players = Player::orderBy('created_at', 'desc')->get();
        return view('players.register', compact('nextUid', 'players'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'dob' => 'required|date',
            'sex' => 'required|in:M,F',
            'password' => 'required|min:6',
        ]);

        $uid = $this->getNextAvailableUid();
        $dob = Carbon::parse($validated['dob']);
        $age = $dob->diffInYears(Carbon::now());

        Player::create([
            'uid' => $uid,
            'name' => $validated['name'],
            'dob' => $dob,
            'sex' => $validated['sex'],
            'password' => Hash::make($validated['password']),
            'age' => $age,
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('players.index')->with('success', 'Player registered successfully!');
    }

    // ✅ Fix edit function to show all players in a table for inline editing
    public function edit()
    {
        $players = Player::orderBy('uid', 'desc')->get();
        return view('players.edit', compact('players'));
    }

    // ✅ Fix update function for inline editing
    public function update(Request $request, $uid)
    {
        $player = Player::where('uid', $uid)->first();
        if (!$player) {
            return response()->json([
                'success' => false, 
                'message' => 'Player not found'
            ], 404);
        }
    
        // Optionally, validate the input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'dob'  => 'required|date',
            'sex'  => 'required|string'
        ]);
    
        $player->update($validated);
    
        return response()->json([
            'success' => true,
            'player' => $player
        ]);
    }
    


    public function destroy($uid)
    {
        $player = Player::where('uid', $uid)->firstOrFail();
        $player->delete();

        return response()->json(['success' => true]);
    }

    private function getNextAvailableUid()
    {
        $usedUids = Player::orderBy('uid')->pluck('uid')->toArray();
        $nextUid = 100001;

        while (in_array($nextUid, $usedUids)) {
            $nextUid++;
        }

        return $nextUid;
    }
    
    public function ranking(Request $request)
    {
        $selectedTournament = $request->input('tournament_id');
        $selectedCategory = $request->input('category_id');
        $selectedPlayer = $request->input('player_id');
        $selectedDate = $request->input('date');
    
        $query = Player::select(
                'players.id',
                'players.uid',
                'players.name',
                'players.age',
                'players.sex',
                'categories.name as category_name',
                DB::raw('COUNT(matches.id) as matches_played'),
                DB::raw('COALESCE(SUM(
                    CASE
                        WHEN matches.player1_id = players.id THEN matches.set1_player1_points + matches.set2_player1_points + matches.set3_player1_points
                        WHEN matches.player2_id = players.id THEN matches.set1_player2_points + matches.set2_player2_points + matches.set3_player2_points
                        ELSE 0
                    END
                ), 0) as total_points')
            )
            ->leftJoin('matches', function($join) {
                $join->on('players.id', '=', 'matches.player1_id')
                    ->orOn('players.id', '=', 'matches.player2_id');
            })
            ->leftJoin('categories', 'players.category_id', '=', 'categories.id')
            ->groupBy('players.id', 'players.uid', 'players.name', 'players.age', 'players.sex', 'categories.name')
            ->orderByDesc('total_points');
    
        if ($selectedTournament) {
            $query->where('matches.tournament_id', $selectedTournament);
        }
    
        if ($selectedCategory) {
            $query->where('matches.category_id', $selectedCategory);
        }
    
        if ($selectedPlayer) {
            $query->where('players.id', $selectedPlayer);
        }
    
        if ($selectedDate) {
            $query->whereDate('matches.match_date', $selectedDate);
        }
    
        $rankings = $query->get()->map(function($player, $index) {
            $player->ranking = $index + 1;
            return $player;
        });
    
        return view('players.players_ranking', [
            'rankings' => $rankings,
            'playersList' => Player::orderBy('name')->get(),
            'tournaments' => Tournament::orderBy('name')->get(),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }
    
    public function doublesRanking(Request $request)
    {
        $selectedTournament = $request->input('tournament_id');
        $selectedCategory = $request->input('category_id');
        $selectedPlayer = $request->input('player_id');
        $selectedDate = $request->input('date');
    
        $tournaments = Tournament::orderBy('name')->get();
    
        $categories = Category::query()
            ->where('name', 'LIKE', '%BD%')
            ->orWhere('name', 'LIKE', '%GD%')
            ->orWhere('name', 'LIKE', '%XD%')
            ->orderBy('name')
            ->get();
    
        $playersList = Player::orderBy('name')->get();
    
        $query = DB::table('matches')
            ->select(
                'categories.name as category_name',
                DB::raw("CONCAT(p1.name, ' / ', p2.name) as team_name"),
                DB::raw('COUNT(matches.id) as matches_played'),
                DB::raw('SUM(matches.set1_team1_points + matches.set2_team1_points + matches.set3_team1_points) as total_points')
            )
            ->join('categories', 'matches.category_id', '=', 'categories.id')
            ->join('players as p1', 'matches.team1_player1_id', '=', 'p1.id')
            ->join('players as p2', 'matches.team1_player2_id', '=', 'p2.id')
            ->join('tournaments', 'matches.tournament_id', '=', 'tournaments.id');
    
        if ($selectedTournament) {
            $query->where('matches.tournament_id', $selectedTournament);
        }
    
        if ($selectedCategory) {
            $query->where('matches.category_id', $selectedCategory);
        }
    
        if ($selectedPlayer) {
            $query->where(function ($query) use ($selectedPlayer) {
                $query->where('matches.team1_player1_id', $selectedPlayer)
                      ->orWhere('matches.team1_player2_id', $selectedPlayer)
                      ->orWhere('matches.team2_player1_id', $selectedPlayer)
                      ->orWhere('matches.team2_player2_id', $selectedPlayer);
            });
        }
    
        if ($selectedDate) {
            $query->whereDate('matches.match_date', $selectedDate);
        }
    
        $rankings = $query->groupBy('team_name', 'categories.name')
                          ->orderBy('categories.name')
                          ->orderByDesc('total_points')
                          ->get();
    
        return view('players.doubles_ranking', compact('rankings', 'tournaments', 'categories', 'playersList'));
    }
    
    }
