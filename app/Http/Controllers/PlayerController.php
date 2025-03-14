<?php

namespace App\Http\Controllers;
use App\Mail\PlayerDeleted;

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
        return view('players.register', ['nextUid' => $this->getNextAvailableUid()]);
    }

    /**
     * Handle player registration.
     */
    public function register(Request $request)
    {
        // Automatically generate the UID.
        $uid = $this->getNextAvailableUid();

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:players,email',
            'mobile'   => 'required|string|max:15|unique:players,mobile',
            'dob'      => 'required|date',
            'sex'      => 'required|in:M,F',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $player = Player::create([
            'uid'        => $uid,
            'name'       => $validated['name'],
            'email'      => $validated['email'],
            'mobile'     => $validated['mobile'],
            'dob'        => $validated['dob'],
            'sex'        => $validated['sex'],
            'ip_address' => $request->ip(),
            'password'   => Hash::make($validated['password']),
        ]);

        // Send email notification on registration.
        // Pass auth()->user() as the initiator. If self-registering, it is the same as the player.
        $this->sendPlayerEmailNotification($player, 'registered', auth()->user());

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
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:players,email|max:255',
            'mobile'   => 'required|string|max:15|unique:players,mobile',
            'dob'      => 'required|date',
            'sex'      => 'required|in:M,F',
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

        $this->sendPlayerEmailNotification($player, 'registered', auth()->user());

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
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:players,email,' . $player->id,
            'mobile'   => 'required|string|max:15|unique:players,mobile,' . $player->id,
            'dob'      => 'required|date',
            'sex'      => 'required|in:M,F',
            'password' => 'nullable|min:6',
        ]);

        $dob = Carbon::parse($validated['dob']);

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

        // Send email notification on update.
        $this->sendPlayerEmailNotification($player, 'updated', auth()->user());

        return response()->json([
            'success' => true,
            'player'  => [
                'uid'    => $player->uid,
                'name'   => $player->name,
                'email'  => $player->email,
                'mobile' => $player->mobile,
                'dob'    => $player->dob->format('Y-m-d'),
                'sex'    => $player->sex,
            ],
        ]);
    }

    /**
     * Delete a player via AJAX.
     */
    public function destroy($uid)
{
    $player = Player::where('uid', $uid)->firstOrFail();
    $deletedPlayerEmail = $player->email; // Get the email before deleting
    $deletedPlayerName = $player->name;
    
    // Capture the user who performed the deletion
    $deletedBy = auth()->user();
    $deletedByEmail = $deletedBy->email ?? null;
    $deletedByUsername = $deletedBy->username ?? 'System';

    // Admin Email
    $adminEmail = "xpindia@gmail.com";

    // Delete the player
    $player->delete();

    // Prepare recipient list
    $recipients = array_unique(array_filter([$adminEmail, $deletedByEmail, $deletedPlayerEmail]));

    // Send email notification
    Mail::to($recipients)->send(new PlayerDeleted($deletedPlayerName, $deletedByUsername));

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
     *
     * @param Player $player
     * @param string $action ('registered' or 'updated')
     * @param mixed  $modifiedBy
     */
    private function sendPlayerEmailNotification($player, $action, $modifiedBy)
    {
        $adminEmail = "xpindia@gmail.com";
        $initiatorEmail = auth()->user()->email ?? null;
        $playerEmail = $player->email;

        // Create a unique array of recipients
        $recipients = array_unique(array_filter([$adminEmail, $initiatorEmail, $playerEmail]));

        Mail::to($recipients)->send(new PlayerNotification($player, $action, $modifiedBy->username));
    }
}
