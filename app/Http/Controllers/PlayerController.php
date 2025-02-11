<?php
namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlayerController extends Controller
{
    // Apply the auth middleware (and any role-check middleware if available)
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Display the form and list of players linked to the current user.
    public function index()
    {
        $user = Auth::user();
        // Assuming a many-to-many relationship between users and players.
        $players = $user->players()->with('users')->get();

        return view('players.index', compact('players'));
    }

    // Handle the POST request to add/link a player.
    public function store(Request $request)
    {
        // Validate input; you can add more rules as needed.
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'dob'  => 'required|date',
            'age'  => 'required|integer',
            'sex'  => 'required|in:M,F',
            'uid'  => 'nullable|string|max:255',
        ]);

        $user = Auth::user();

        // Example role check (adjust according to your project’s implementation)
        if (!$user->is_admin && !$user->is_user) {
            abort(403, 'Access denied.');
        }

        // Use provided UID or generate one.
        $uid = $data['uid'] ?? uniqid('UID_');
        $data['uid'] = $uid;

        // Check if player already exists by UID.
        $player = Player::where('uid', $uid)->first();

        if ($player) {
            // If the player exists, link it if not already linked.
            if (!$player->users->contains($user->id)) {
                $player->users()->attach($user->id);
                $message = "Player linked to your account.";
            } else {
                $message = "Player already linked to your account.";
            }
        } else {
            // Create a new player.
            $data['created_by'] = $user->id;
            $player = Player::create($data);
            // Link the new player to the current user.
            $player->users()->attach($user->id);
            $message = "Player added and linked successfully.";
        }

        return redirect()->route('players.index')->with('message', $message);
    }

    // Optionally add methods for editing and deleting players (accessible by admins).
}
