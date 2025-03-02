<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class PlayerController extends Controller
{
    public function index()
    {
        $players = Player::orderBy('uid', 'desc')->paginate(10); // Paginate results (10 per page)
        $nextUid = $this->getNextAvailableUid();
        $showRegistration = auth()->check(); // Ensure registration form only shows when logged in
    
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

    // Calculate age from DOB
    $dob = Carbon::parse($request->dob);
    $age = $dob->diffInYears(Carbon::now());

    // Get the next available UID
    $uid = $this->getNextAvailableUid();  // ✅ Ensuring UID is assigned

    Player::create([
        'uid' => $uid,  // ✅ Add UID here
        'name' => $request->name,
        'dob' => $dob,
        'sex' => $request->sex,
        'password' => Hash::make($request->password),
        'age' => $age,
        'ip_address' => $request->ip(),
    ]);

    return redirect()->route('players.register')->with('success', 'Player registered successfully!');
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
    $age = $dob->diffInYears(Carbon::now()); // ✅ Calculate age

    $player = new Player();
    $player->uid = $uid;
    $player->name = $validated['name'];
    $player->dob = $dob;
    $player->sex = $validated['sex'];
    $player->password = Hash::make($validated['password']);
    $player->ip_address = request()->ip();
    $player->age = $age; // ✅ Ensure 'age' is inserted
    $player->save();

    return redirect()->back()->with('success', 'Player registered successfully!');
}



    public function edit($id)
    {
        $player = Player::findOrFail($id);
        return view('players.edit', compact('player'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'dob' => 'required|date',
            'sex' => 'required|in:M,F',
            'password' => 'nullable|min:6',
            'uid' => 'required|integer|min:100001|unique:players,uid,' . $id, // Ensure UID is unique but editable
        ]);

        $player = Player::findOrFail($id);
        $player->uid = $request->uid;
        $player->name = $request->name;
        $player->dob = $request->dob;
        $player->sex = $request->sex;

        if ($request->filled('password')) {
            $player->password = bcrypt($request->password);
        }

        $player->save();

        return redirect()->route('player.register')->with('success', 'Player updated successfully!');
    }

    private function getNextAvailableUid()
    {
        $usedUids = Player::orderBy('uid')->pluck('uid')->toArray(); // Get all existing UIDs
        $nextUid = 100001; // Start from 100001

        while (in_array($nextUid, $usedUids)) {
            $nextUid++; // Keep increasing until a free UID is found
        }

        return $nextUid;
    }
}

