<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\PlayerNotification;

class PlayerController extends Controller
{
    /**
     * Display a listing of players.
     */
    public function index()
    {
        $players = Player::orderBy('uid', 'desc')->paginate(10);
        $nextUid = $this->getNextAvailableUid();
        $showRegistration = auth()->check();

        return view('players.index', compact('players', 'nextUid', 'showRegistration'));
    }

    /**
     * Show the registration form.
     */
    public function showRegistrationForm()
    {
        // For registration, you might want to return a dedicated view.
        // For now, we use the same as index if that's intended.
        return view('players.register', ['nextUid' => $this->getNextAvailableUid()]);
    }

    /**
     * Handle player registration.
     */
    public function register(Request $request)
{
    // Automatically generate the UID.
    $uid = $this->getNextAvailableUid();

    // Validate the registration form data.
    $validated = $request->validate([
        'name'     => 'required|string|max:255',
        'email'    => 'required|email|unique:players,email',
        'mobile'   => 'required|string|max:15|unique:players,mobile',
        'dob'      => 'required|date',
        'sex'      => 'required|in:M,F',
        'password' => 'required|string|min:6|confirmed',
    ]);

    // Create the player, including mobile and a hashed password.
    $player = Player::create([
        'uid'        => $uid,
        'name'       => $validated['name'],
        'email'      => $validated['email'],
        'mobile'     => $validated['mobile'],  // Ensure mobile is passed here
        'dob'        => $validated['dob'],
        'sex'        => $validated['sex'],
        'ip_address' => $request->ip(),
        'password'   => Hash::make($validated['password']),
    ]);

    return redirect()->route('players.index')->with('success', 'Registration successful!');
}


    /**
     * Show the registration form (alternate method).
     */
    public function create()
    {
        $nextUid = $this->getNextAvailableUid();
        return view('players.register', compact('nextUid'));
    }

    /**
     * Store a new player (alternate registration method).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|unique:players,email|max:255',
            'mobile' => 'required|string|max:15|unique:players,mobile',
            'dob'    => 'required|date',
            'sex'    => 'required|in:M,F',
            'password' => 'required|min:6',
        ]);

        $uid = $this->getNextAvailableUid();
        $dob = Carbon::parse($validated['dob']);

        $player = Player::create([
            'uid'        => $uid,
            'name'       => $validated['name'],
            'email'      => $validated['email'],
            'mobile'     => $validated['mobile'],
            'dob'        => $dob,
            'sex'        => $validated['sex'],
            'password'   => Hash::make($validated['password']),
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('players.index')->with('success', 'Player registered successfully!');
    }

    /**
     * Display inline editing view for players.
     * URL: /players/edit (no UID parameter needed)
     */
    public function edit()
    {
        $players = Player::orderBy('uid', 'desc')->paginate(10);
        return view('players.edit', compact('players'));
    }

    /**
     * Update player details via AJAX.
     */
    public function update(Request $request, $uid)
{
    $player = Player::where('uid', $uid)->first();
    if (!$player) {
        return response()->json([
            'success' => false, 
            'message' => 'Player not found'
        ], 404);
    }

    $validated = $request->validate([
        'name'   => 'required|string|max:255',
        'email'  => 'required|email|max:255|unique:players,email,' . $player->id,
        'mobile' => 'required|string|max:15|unique:players,mobile,' . $player->id,
        'dob'    => 'required|date',
        'sex'    => 'required|in:M,F',
        'password' => 'nullable|min:6', // Optional on update
    ]);

    $dob = \Carbon\Carbon::parse($validated['dob']);

    $updateData = [
        'name'   => $validated['name'],
        'email'  => $validated['email'],
        'mobile' => $validated['mobile'],
        'dob'    => $dob,
        'sex'    => $validated['sex'],
    ];

    if (!empty($validated['password'])) {
        $updateData['password'] = Hash::make($validated['password']);
    }

    $player->update($updateData);

    // Return the updated player data with formatted dob
    return response()->json([
        'success' => true,
        'player' => [
            'uid'   => $player->uid,
            'name'  => $player->name,
            'email' => $player->email,
            'mobile'=> $player->mobile,
            'dob'   => $player->dob->format('Y-m-d'),
            'sex'   => $player->sex,
            // include any other fields you need
        ],
    ]);
}


    /**
     * Delete a player via AJAX.
     */
    public function destroy($uid)
    {
        $player = Player::where('uid', $uid)->firstOrFail();
        $player->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Helper method to generate the next available UID.
     */
    protected function getNextAvailableUid()
    {
        $lastPlayer = Player::orderBy('uid', 'desc')->first();
        return $lastPlayer ? $lastPlayer->uid + 1 : 100001;
    }

    /**
     * Send email notification on player creation or update.
     */
    private function sendPlayerEmailNotification($player, $action, $modifiedBy)
    {
        $adminEmail = "xpindia@gmail.com";
        $creatorEmail = auth()->user()->email ?? null;
        $modifierEmail = $modifiedBy->email ?? null;
        $newPlayerEmail = $player->email ?? null;

        $recipients = array_filter([$creatorEmail, $modifierEmail, $adminEmail, $newPlayerEmail]);

        if (!empty($recipients)) {
            Mail::to($modifierEmail)
                ->cc($recipients)
                ->send(new PlayerNotification($player, $action, $modifiedBy->username));
        }
    }
}
