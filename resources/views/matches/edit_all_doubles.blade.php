@extends('layouts.app')

@section('title', 'Edit All Doubles Matches')

@section('content')
    <h2>Edit All Doubles Matches</h2>
    <p>Select the doubles matches you want to edit.</p>
    <form method="POST" action="{{ route('matches.store') }}">
        @csrf
        <label for="match_id">Match ID:</label>
        <input type="text" name="match_id" required>

        <label for="team1">Team 1:</label>
        <input type="text" name="team1" required>

        <label for="team2">Team 2:</label>
        <input type="text" name="team2" required>

        <button type="submit">Update Match</button>
    </form>
@endsection
