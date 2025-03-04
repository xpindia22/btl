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
}
