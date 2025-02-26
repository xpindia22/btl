<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Matches;
use App\Models\Tournament;
use App\Models\Category;
use App\Models\Player;

class MatchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Index Singles Matches
    public function indexSingles()
    {
        $matches = Matches::where('match_type', 'singles')->get();
        return view('matches.singles.index', compact('matches'));
    }

    // Index Doubles Matches
    public function indexDoubles()
    {
        $matches = Matches::where('match_type', 'doubles')->get();
        return view('matches.doubles.index', compact('matches'));
    }

    // Lock/unlock singles tournaments
    public function lockSinglesTournament(Request $request)
    {
        $validated = $request->validate([
            'tournament_id' => 'required|exists:tournaments,id'
        ]);

        session(['locked_singles_tournament_id' => $validated['tournament_id']]);
        return redirect()->back()->with('success', 'Singles tournament locked successfully.');
    }

    public function unlockSinglesTournament()
    {
        session()->forget('locked_singles_tournament_id');
        return redirect()->back()->with('success', 'Singles tournament unlocked successfully.');
    }

    // Lock/unlock doubles tournaments
    public function lockDoublesTournament(Request $request)
    {
        $validated = $request->validate([
            'tournament_id' => 'required|exists:tournaments,id'
        ]);

        session(['locked_doubles_tournament_id' => $validated['tournament_id']]);
        return redirect()->back()->with('success', 'Doubles tournament locked successfully.');
    }

    public function unlockDoublesTournament()
    {
        session()->forget('locked_doubles_tournament_id');
        return redirect()->back()->with('success', 'Doubles tournament unlocked successfully.');
    }

    // Fetch players for singles category
    public function filteredPlayersSingles(Request $request)
    {
        $category = Category::find($request->category_id);

        if (!$category) {
            return response()->json([]);
        }

        $ageLimit = $category->age_limit;
        $sex = $category->type === 'BS' ? 'Male' : 'Female';

        $players = Player::where('sex', $sex)
                          ->where('age', '<=', $ageLimit)
                          ->select('id', 'name', 'age', 'sex')
                          ->get();

        return response()->json($players);
    }

    // Fetch players for doubles category
    public function filteredPlayersDoubles(Request $request)
    {
        $category = Category::find($request->category_id);

        if (!$category) {
            return response()->json([]);
        }

        $ageLimit = $category->age_limit;
        $sex = in_array($category->type, ['BD', 'GD']) ? 'Male' : 'Female';

        $players = Player::where('age', '<=', $ageLimit)
                          ->where(function ($query) use ($category) {
                              if ($category->type === 'XD') {
                                  $query->whereIn('sex', ['Male', 'Female']);
                              } else {
                                  $query->where('sex', $category->type === 'BD' ? 'Male' : 'Female');
                              }
                          })
                          ->select('id', 'name', 'age', 'sex')
                          ->get();

        return response()->json($players);
    }
}