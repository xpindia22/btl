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
            'name'     => ['required', 'regex:/^[a-zA-Z ]+$/'],
            'dob'      => 'required|date',
            'sex'      => 'required|in:M,F',
            'password' => 'required|min:6',
        ]);

        $uid = $this->getNextAvailableUid(); // Get next UID properly

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

        return redirect()->route('player.register')->with('success', 'Player registered successfully!');
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

        $uid = $this->getNextAvailableUid(); // Get next available UID

        $player = new Player();
        $player->uid = $uid;
        $player->name = $validated['name'];
        $player->dob = $validated['dob'];
        $player->sex = $validated['sex'];
        $player->password = bcrypt($validated['password']);
        $player->ip_address = request()->ip();
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
