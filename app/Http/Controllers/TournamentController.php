<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tournament;

class TournamentController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Tournament::class);
        return view('tournaments.index');
    }

    public function show(Tournament $tournament)
    {
        $this->authorize('view', $tournament);
        return view('tournaments.show', compact('tournament'));
    }

    public function create()
    {
        $this->authorize('create', Tournament::class);
        return view('tournaments.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Tournament::class);

        // Validate & Create Tournament
        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        Tournament::create($request->all());

        return redirect()->route('tournaments.index')->with('success', 'Tournament created successfully.');
    }

    public function edit(Tournament $tournament)
    {
        $this->authorize('update', $tournament);
        return view('tournaments.edit', compact('tournament'));
    }

    public function update(Request $request, Tournament $tournament)
    {
        $this->authorize('update', $tournament);

        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        $tournament->update($request->all());

        return redirect()->route('tournaments.index')->with('success', 'Tournament updated successfully.');
    }

    public function destroy(Tournament $tournament)
    {
        $this->authorize('delete', $tournament);
        $tournament->delete();

        return redirect()->route('tournaments.index')->with('success', 'Tournament deleted successfully.');
    }
}
