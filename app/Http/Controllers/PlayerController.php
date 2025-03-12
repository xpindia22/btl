<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Tournament;
use App\Models\Category;
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

        $player = Player::create([
            'uid' => $uid,
            'name' => $request->name,
            'dob' => $dob,
            'sex' => $request->sex,
            'password' => Hash::make($request->password),
            'age' => $age,
            'ip_address' => $request->ip(),
        ]);

        // Send email notification
        $this->sendPlayerEmailNotification($player, 'created', auth()->user());

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

        $player = Player::create([
            'uid' => $uid,
            'name' => $validated['name'],
            'dob' => $dob,
            'sex' => $validated['sex'],
            'password' => Hash::make($validated['password']),
            'age' => $age,
            'ip_address' => request()->ip(),
        ]);

        // Send email notification
        $this->sendPlayerEmailNotification($player, 'created', auth()->user());

        return redirect()->route('players.index')->with('success', 'Player registered successfully!');
    }

    public function edit()
    {
        $players = Player::paginate(10);
        return view('players.edit', compact('players'));
    }

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
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'dob'   => 'required|date',
            'sex'   => 'required|string|max:10',
        ]);

        $player->update($validated);

        // Send email notification
        $this->sendPlayerEmailNotification($player, 'updated', auth()->user());

        return response()->json([
            'success' => true,
            'player'  => $player
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

    /**
     * Send email notification on player creation or update.
     */
    private function sendPlayerEmailNotification($player, $action, $modifiedBy)
{
    $adminEmail = "xpindia@gmail.com";
    $creatorEmail = auth()->user()->email ?? null; // Get creator's email
    $modifierEmail = $modifiedBy->email ?? null; // Get modifier's email
    $newPlayerEmail = $player->email ?? null; // Get updated player email

    // Include the new email of the player if it's different from the previous email
    $recipients = array_filter([$creatorEmail, $modifierEmail, $adminEmail, $newPlayerEmail]);

    if (!empty($recipients)) {
        Mail::to($modifierEmail)
            ->cc($recipients)
            ->send(new PlayerNotification($player, $action, $modifiedBy->username));
    }
}

}
