<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Match;

class MatchController extends Controller
{
    public function index()
    {
        $this->authorize('viewAnyMatches', Match::class);
        return view('matches.index');
    }

    public function create()
    {
        $this->authorize('createMatch', Match::class);
        return view('matches.create');
    }

    public function store(Request $request)
    {
        $this->authorize('createMatch', Match::class);

        // Validate request data
        $request->validate([
            'tournament_id' => 'required|exists:tournaments,id',
            'player1_id' => 'required|exists:players,id',
            'player2_id' => 'required|exists:players,id',
            'match_date' => 'required|date',
        ]);

        Match::create($request->all());

        return redirect()->route('matches.index')->with('success', 'Match created successfully.');
    }

    public function show(Match $match)
    {
        $this->authorize('view', $match);
        return view('matches.show', compact('match'));
    }

    public function edit(Match $match)
    {
        $this->authorize('update', $match);
        return view('matches.edit', compact('match'));
    }

    public function update(Request $request, Match $match)
    {
        $this->authorize('update', $match);

        $request->validate([
            'match_date' => 'required|date',
        ]);

        $match->update($request->all());

        return redirect()->route('matches.index')->with('success', 'Match updated successfully.');
    }

    public function destroy(Match $match)
    {
        $this->authorize('deleteMatch', $match);
        $match->delete();

        return redirect()->route('matches.index')->with('success', 'Match deleted successfully.');
    }
}
