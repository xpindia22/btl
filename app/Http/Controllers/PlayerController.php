<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class PlayerController extends Controller
{
    /**
     * Public listing of players.
     * Accessible to everyone (logged in or not).
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Retrieve all players for public listing
        $players = Player::orderBy('uid', 'desc')->get();

        // Calculate next available UID (for use if registration form is shown)
        $nextUid = (int)(Player::max('uid') ?? 0) + 1;

        // Flag to show the registration form only if the visitor is logged in
        $showRegistration = auth()->check();

        return view('players.register', compact('players', 'nextUid', 'showRegistration'));
    }

    /**
     * Show the player registration form.
     * (This may be the same as index if you wish.)
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return $this->index();
    }

    /**
     * Process the player registration form submission.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        $request->validate([
            'uid'      => 'nullable|integer|min:1',
            'name'     => ['required', 'regex:/^[a-zA-Z ]+$/'],
            'dob'      => 'required|date',
            'sex'      => 'required|in:M,F',
            'password' => 'required|min:6',
        ]);

        $uid = $request->input('uid') ?: ((int)(Player::max('uid') ?? 0) + 1);

        if (Player::where('uid', $uid)->exists()) {
            return back()
                ->with('message', 'Error: UID already exists. Please choose another.')
                ->withInput();
        }

        $age = Carbon::parse($request->dob)->age;

        // Create the player and record the creator (if your table supports it)
        Player::create([
            'uid'       => $uid,
            'name'      => $request->name,
            'dob'       => $request->dob,
            'age'       => $age,
            'sex'       => $request->sex,
            'password'  => Hash::make($request->password),
            'created_by'=> auth()->id(), // Assumes the players table has this field
        ]);

        return redirect()->route('player.register')->with('success', 'Player registered successfully!');
    }

    /**
     * Show a dedicated form for creating a new player.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $nextUid = (int)(Player::max('uid') ?? 0) + 1;
        return view('players.create', compact('nextUid'));
    }

    /**
     * Store a newly created player in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'uid'      => 'nullable|integer|min:1',
            'name'     => ['required', 'regex:/^[a-zA-Z ]+$/'],
            'dob'      => 'required|date',
            'sex'      => 'required|in:M,F',
            'password' => 'required|min:6',
        ]);

        $uid = $request->input('uid') ?: ((int)(Player::max('uid') ?? 0) + 1);

        if (Player::where('uid', $uid)->exists()) {
            return back()
                ->with('message', 'Error: UID already exists. Please choose another.')
                ->withInput();
        }

        $age = Carbon::parse($request->dob)->age;

        Player::create([
            'uid'       => $uid,
            'name'      => $request->name,
            'dob'       => $request->dob,
            'age'       => $age,
            'sex'       => $request->sex,
            'password'  => Hash::make($request->password),
            'created_by'=> auth()->id(),
        ]);

        return redirect()->route('players.index')->with('success', 'Player created successfully!');
    }

    /**
     * Display the management view for players.
     * Admins see all players; other users see only players they created.
     *
     * @return \Illuminate\View\View
     */
    public function manage()
    {
        $user = auth()->user();
        if ($user->role === 'admin') {
            // Admins can manage all players
            $players = Player::orderBy('uid', 'desc')->get();
        } else {
            // For non-admins, show only players created by the current user
            $players = Player::where('created_by', $user->id)
                ->orderBy('uid', 'desc')
                ->get();
        }

        return view('players.manage', compact('players'));
    }

    /**
     * Show the form for editing the specified player.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $player = Player::findOrFail($id);
        return view('players.edit', compact('player'));
    }

    /**
     * Update the specified player in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $player = Player::findOrFail($id);

        $request->validate([
            'name'     => ['required', 'regex:/^[a-zA-Z ]+$/'],
            'dob'      => 'required|date',
            'sex'      => 'required|in:M,F',
            'password' => 'nullable|min:6',
        ]);

        $player->name = $request->name;
        $player->dob  = $request->dob;
        $player->age  = Carbon::parse($request->dob)->age;
        $player->sex  = $request->sex;

        if ($request->filled('password')) {
            $player->password = Hash::make($request->password);
        }

        $player->save();

        return redirect()->route('players.manage')->with('success', 'Player updated successfully!');
    }

    /**
     * Remove the specified player from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $player = Player::findOrFail($id);
        $player->delete();

        return redirect()->route('players.manage')->with('success', 'Player deleted successfully!');
    }



    public function getPlayers(Request $request)
    {
        $category_id = $request->input('category_id');
        if (!$category_id) {
            return response()->json([]);
        }

        // Retrieve the category details using Eloquent
        $category = Category::find($category_id);
        if (!$category) {
            return response()->json([]);
        }

        $age_group = $category->age_group;
        $sex = $category->sex;
        $category_name = $category->name;

        if (!$age_group || !$sex || !$category_name) {
            return response()->json([]);
        }

        // Set default min and max dates of birth
        $min_dob = "1900-01-01"; 
        $max_dob = date("Y-m-d");

        if (strpos($age_group, 'Under') !== false) {
            preg_match('/Under\s+(\d+)/i', $age_group, $matches);
            if (isset($matches[1])) {
                $max_age = intval($matches[1]);
                $min_dob = date("Y-m-d", strtotime("-{$max_age} years -1 day")); 
            }
        } elseif (strpos($age_group, 'Over') !== false) {
            preg_match('/Over\s+(\d+)/i', $age_group, $matches);
            if (isset($matches[1])) {
                $min_age = intval($matches[1]);
                $max_dob = date("Y-m-d", strtotime("-{$min_age} years")); 
            }
        } elseif (strpos($age_group, 'Between') !== false) {
            preg_match('/Between\s+(\d+)\s*-\s*(\d+)/i', $age_group, $matches);
            if (isset($matches[1]) && isset($matches[2])) {
                $min_age = intval($matches[1]);
                $max_age = intval($matches[2]);
                $max_dob = date("Y-m-d", strtotime("-{$min_age} years")); 
                $min_dob = date("Y-m-d", strtotime("-{$max_age} years -1 day")); 
            }
        }

        // Query players based on the category name and sex
        if (strpos($category_name, 'XD') !== false) {
            $players = DB::table('players')
                ->select('id','name','dob','sex')
                ->whereBetween('dob', [$min_dob, $max_dob])
                ->get();
        } else {
            $players = DB::table('players')
                ->select('id','name','dob','sex')
                ->where('sex', $sex)
                ->whereBetween('dob', [$min_dob, $max_dob])
                ->get();
        }

        // Calculate each player's age
        $players = $players->map(function($player) {
            $dob = strtotime($player->dob);
            $age = date("Y") - date("Y", $dob);
            if (date("md", $dob) > date("md")) {
                $age--;
            }
            $player->age = $age;
            return $player;
        });

        return response()->json($players);
    }
}
