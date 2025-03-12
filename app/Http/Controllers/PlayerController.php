<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;
use Illuminate\Support\Facades\Hash; // ✅ Ensure this line is correct
 

use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\PlayerNotification;

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

    use Illuminate\Support\Facades\Hash;

public function register(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:players,email|max:255',
        'mobile' => 'required|string|max:15|unique:players,mobile',
        'dob' => 'required|date',
        'sex' => 'required|in:M,F',
        'password' => 'required|min:6',
    ]);

    $uid = $this->getNextAvailableUid();
    $dob = Carbon::parse($validated['dob']);

    // Ensure password is hashed and included
    $player = Player::create([
        'uid' => $uid,
        'name' => $validated['name'],
        'email' => $validated['email'],
        'mobile' => $validated['mobile'],
        'dob' => $dob,
        'sex' => $validated['sex'],
        'password' => Hash::make($validated['password']), // ✅ Hash password
        'ip_address' => $request->ip(),
    ]);

    return redirect()->route('players.index')->with('success', 'Player registered successfully!');
}

    public function create()
    {
        $nextUid = $this->getNextAvailableUid();
        return view('players.register', compact('nextUid'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:players,email|max:255',
        'mobile' => 'required|string|max:15|unique:players,mobile',
        'dob' => 'required|date',
        'sex' => 'required|in:M,F',
        'password' => 'required|min:6', // Ensure password is required
    ]);

    $uid = $this->getNextAvailableUid();
    $dob = Carbon::parse($validated['dob']);

    $player = Player::create([
        'uid' => $uid,
        'name' => $validated['name'],
        'email' => $validated['email'],
        'mobile' => $validated['mobile'],
        'dob' => $dob,
        'sex' => $validated['sex'],
        'password' => Hash::make($validated['password']), // Ensure password is hashed
        'ip_address' => $request->ip(),
    ]);

    return redirect()->route('players.index')->with('success', 'Player registered successfully!');
}

    public function edit($uid)
    {
        $player = Player::where('uid', $uid)->first();
        if (!$player) {
            return redirect()->route('players.index')->with('error', 'Player not found.');
        }
        return view('players.edit', compact('player'));
    }

    public function update(Request $request, $uid)
    {
        // Validate the request data
        $player = Player::where('uid', $uid)->first();
        if (!$player) {
            return response()->json([
                'success' => false, 
                'message' => 'Player not found'
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:players,email,' . $player->id,
            'mobile' => 'required|string|max:15|unique:players,mobile,' . $player->id,
            'dob' => 'required|date',
            'sex' => 'required|string|max:10',
            'password' => 'nullable|min:6', // Password is optional in update
        ]);

        // Calculate age from DOB
        $dob = Carbon::parse($validated['dob']);

        // Update the player record
        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'mobile' => $validated['mobile'],
            'dob' => $dob,
            'sex' => $validated['sex'],
        ];

        // Check if password was provided, and hash it
        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $player->update($updateData);

        // Send email notification on player update
        $this->sendPlayerEmailNotification($player, 'updated', auth()->user());

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
        // Get the highest existing UID and increment it
        $maxUid = Player::max('uid');
        return $maxUid ? $maxUid + 1 : 100001;
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
