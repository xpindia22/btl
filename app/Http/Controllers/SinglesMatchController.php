<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Matches as MatchModel;
use App\Models\Tournament;
use App\Models\Category;
use App\Models\Player;
use App\Services\MatchNotificationService;

class SinglesMatchController extends Controller
{
    protected $notificationService;

    public function __construct(MatchNotificationService $notificationService)
    {
        $this->middleware('auth');
        $this->notificationService = $notificationService;
    }

    // Lock/unlock Singles tournaments
    public function lockTournament(Request $request)
    {
        $validated = $request->validate(['tournament_id' => 'required|exists:tournaments,id']);
        session(['locked_singles_tournament_id' => $validated['tournament_id']]);
        return redirect()->back()->with('success', 'Singles tournament locked successfully.');
    }

    public function unlockTournament()
    {
        session()->forget('locked_singles_tournament_id');
        return redirect()->back()->with('success', 'Singles tournament unlocked successfully.');
    }

    // Create Singles Match View
    public function create()
    {
        $user = Auth::user();
        $tournaments = Tournament::where('created_by', $user->id)
            ->orWhereHas('moderators', fn($q) => $q->where('user_id', $user->id))
            ->get();

        $lockedTournamentId = session('locked_singles_tournament_id');
        $lockedTournament = $lockedTournamentId ? Tournament::find($lockedTournamentId) : null;

        $categories = Category::where('name', 'LIKE', '%BS%')
            ->orWhere('name', 'LIKE', '%GS%')
            ->get();

        return view('matches.singles.create', compact('tournaments', 'lockedTournament', 'categories'));
    }

    // Fetch players for Singles Matches
    public function filteredPlayers(Request $request)
    {
        \Log::info("🔍 API Request - filteredPlayers()", ['category_id' => $request->category_id]);

        if (!$request->has('category_id')) {
            \Log::error("❌ Missing category_id in request!");
            return response()->json(['error' => 'Category ID is required'], 400);
        }

        $category = Category::find($request->category_id);
        if (!$category) {
            \Log::error("❌ No category found for ID: " . $request->category_id);
            return response()->json([]);
        }

        $categoryName = strtoupper($category->name);
        $sex = $category->sex;
        $minAge = 0;
        $maxAge = 100;

        \Log::info("✅ Category Selected: {$categoryName} | Sex: {$sex}");

        if (preg_match('/^U(\d+)(BS|GS)$/', $categoryName, $matches)) {
            $maxAge = (int) $matches[1];
        } elseif (preg_match('/^SENIOR (\d+) PLUS (BS|GS)$/', $categoryName, $matches)) {
            $minAge = (int) $matches[1];
        }

        $players = Player::where('sex', $sex)
                         ->whereBetween('age', [$minAge, $maxAge])
                         ->whereNotNull('uid')
                         ->where('uid', '!=', '')
                         ->select('id', 'name', 'age', 'sex')
                         ->get();

        \Log::info("✅ Players Found: " . $players->count(), $players->toArray());

        return response()->json($players);
    }


    // Store Singles Match and Handle Payments
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tournament_id' => 'required|exists:tournaments,id',
            'category_id' => 'required|exists:categories,id',
            'match_date' => 'required|date',
            'match_time' => 'required',
            'player1_id' => 'required|exists:players,id',
            'player2_id' => 'required|exists:players,id|different:player1_id',
            'stage' => 'required|string',
            'set1_player1_points' => 'nullable|integer|min:0',
            'set1_player2_points' => 'nullable|integer|min:0',
            'set2_player1_points' => 'nullable|integer|min:0',
            'set2_player2_points' => 'nullable|integer|min:0',
            'set3_player1_points' => 'nullable|integer|min:0',
            'set3_player2_points' => 'nullable|integer|min:0',
        ]);

        try {
            // Save the match
            $match = MatchModel::create($validated + ['created_by' => Auth::id()]);

            // Send match created email notification
            $this->notificationService->sendMatchCreatedNotification($match);

            // Fetch category fee details
            $tournamentCategory = \DB::table('tournament_categories')
                ->where('tournament_id', $request->tournament_id)
                ->where('category_id', $request->category_id)
                ->first();

            if (!$tournamentCategory) {
                Log::error("❌ Tournament category not found! Tournament ID: {$request->tournament_id}, Category ID: {$request->category_id}");
                return back()->withErrors(['error' => 'Tournament category data missing.']);
            }

            Log::info("✅ Tournament Category Data -> is_paid: {$tournamentCategory->is_paid}, Fee: {$tournamentCategory->fee}");

            // If the category is marked as paid, process payments
            if ($tournamentCategory->is_paid == 1 && $tournamentCategory->fee > 0) {
                $categoryFee = $tournamentCategory->fee;
                $players = Player::whereIn('id', [$request->player1_id, $request->player2_id])->get();

                foreach ($players as $player) {
                    // Check if payment record already exists for this player
                    $existingPayment = Payment::where([
                        'user_id' => $player->id,
                        'tournament_id' => $request->tournament_id,
                        'amount' => $categoryFee,
                        'status' => 'Pending'
                    ])->first();

                    if (!$existingPayment) {
                        // Create pending payment record
                        Payment::create([
                            'user_id' => $player->id,
                            'tournament_id' => $request->tournament_id,
                            'amount' => $categoryFee,
                            'payment_method' => 'UPI',
                            'transaction_id' => null,
                            'status' => 'Pending',
                        ]);

                        // Send email to player for pending payment
                        Mail::to($player->email)->send(new PaymentPendingNotification($player, $categoryFee));

                        Log::info("📧 Payment pending email sent to {$player->email} for category ID: {$request->category_id}");
                    } else {
                        Log::info("ℹ️ Payment record already exists for {$player->email}, skipping.");
                    }
                }

                // Send notification to admin about pending payments
                Mail::to("xpindia@gmail.com")->send(new PaymentPendingNotification(null, $categoryFee, true));

                Log::info("📩 Admin notified about pending payments for tournament ID: {$request->tournament_id}");
            } else {
                Log::info("ℹ️ Category ID {$request->category_id} is free, no payment email needed.");
            }

            return redirect()->route('matches.singles.index')
                ->with('success', 'Singles match created successfully.');

        } catch (\Exception $e) {
            Log::error('❌ Error creating match: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to create the match. Please try again.']);
        }
    }

    
    // Display Singles Matches
    public function index(Request $request)
    {
        $tournaments = Tournament::all();
        $players = Player::all();

        $query = MatchModel::with(['tournament', 'category', 'player1', 'player2'])
                ->whereHas('category', function ($q) {
                    $q->where('name', 'LIKE', '%BS%')
                      ->orWhere('name', 'LIKE', '%GS%');
                });

        if ($request->filled('filter_tournament') && $request->filter_tournament !== 'all') {
            $query->where('tournament_id', $request->filter_tournament);
        }

        if ($request->filled('filter_player1') && $request->filter_player1 !== 'all') {
            $query->where('player1_id', $request->filter_player1);
        }

        if ($request->filled('filter_player2') && $request->filter_player2 !== 'all') {
            $query->where('player2_id', $request->filter_player2);
        }

        if ($request->filled('filter_category') && $request->filter_category !== 'all') {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('name', 'LIKE', $request->filter_category);
            });
        }

        if ($request->filled('filter_date')) {
            $query->whereDate('match_date', $request->filter_date);
        }

        if ($request->filled('filter_stage') && $request->filter_stage !== 'all') {
            $query->where('stage', $request->filter_stage);
        }

        $matches = $query->orderBy('created_at', 'desc')->paginate(5);

        return view('matches.singles.index', compact('tournaments', 'players', 'matches'));
    }

    // Update Match
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'stage' => 'required|string',
            'match_date' => 'required|date',
            'match_time' => 'required',
            'set1_player1_points' => 'nullable|integer',
            'set1_player2_points' => 'nullable|integer',
            'set2_player1_points' => 'nullable|integer',
            'set2_player2_points' => 'nullable|integer',
            'set3_player1_points' => 'nullable|integer',
            'set3_player2_points' => 'nullable|integer',
        ]);

        $match = MatchModel::findOrFail($id);
        $match->update($validated);

        // Send Email Notification about the Match Update
        try {
            $this->notificationService->sendMatchUpdatedNotification($match);
            \Log::info("📩 Match Update Notification Sent: Match ID {$match->id}");
        } catch (\Exception $e) {
            \Log::error("❌ Failed to send match update notification: " . $e->getMessage());
        }

        return response()->json(['message' => 'Singles match updated successfully.']);
    }

    // Delete Match
    public function delete($id)
    {
        $match = MatchModel::findOrFail($id);
        $match->delete();

        return response()->json(['message' => 'Match deleted successfully.']);
    }

    // Display Singles Matches for Inline Editing
    public function edit()
    {
        $matches = MatchModel::with(['tournament', 'category', 'player1', 'player2'])
                    ->whereHas('category', function ($q) {
                        $q->where('name', 'LIKE', '%BS%')
                          ->orWhere('name', 'LIKE', '%GS%');
                    })
                    ->orderBy('created_at', 'desc')
                    ->paginate(5);

        return view('matches.singles.edit', compact('matches'));
    }

    public function show($id)
    {
        $match = MatchModel::findOrFail($id);
        return view('matches.singles.show', compact('match'));
    }
}
