<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class PlayerController extends Controller
{
    // Display the registration form and list of players
    public function showRegistrationForm()
    {
        // Calculate the next available UID
        $nextUid = (int) (Player::max('uid') ?? 0) + 1;
        $players = Player::orderBy('uid', 'desc')->get();
        return view('players.register', compact('nextUid', 'players'));
    }

    // Process the registration form submission
    public function register(Request $request)
    {
        // Validate input using Laravel's validation rules
        $request->validate([
            'uid'      => 'nullable|integer|min:1',
            'name'     => ['required', 'regex:/^[a-zA-Z ]+$/'],
            'dob'      => 'required|date',
            'sex'      => 'required|in:M,F',
            'password' => 'required|min:6',
        ]);

        // If UID is not provided, calculate the next available UID
        $uid = $request->input('uid') ?: ((int) (Player::max('uid') ?? 0) + 1);

        // Check if the UID already exists
        if (Player::where('uid', $uid)->exists()) {
            return back()->with('message', 'Error: UID already exists. Please choose another.')->withInput();
        }

        // Calculate the age using Carbon
        $age = Carbon::parse($request->dob)->age;

        // Create the new player record
        Player::create([
            'uid'      => $uid,
            'name'     => $request->name,
            'dob'      => $request->dob,
            'age'      => $age,
            'sex'      => $request->sex,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('player.register')->with('success', 'Player registered successfully!');
    }

    // In app/Http/Controllers/PlayerController.php
public function index()
{
    // If you want to show the registration form as the default view:
    $nextUid = (int) (Player::max('uid') ?? 0) + 1;
    $players = Player::orderBy('uid', 'desc')->get();
    return view('players.register', compact('nextUid', 'players'));
}


}