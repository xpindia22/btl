@extends('layouts.app')

@section('title', 'Create Boys Doubles Match')

@section('content')
    <h2>Create Boys Doubles Match</h2>
    <form method="POST" action="{{ route('matches.store') }}">
        @csrf
        <label for="team1_player1">Team 1 - Player 1:</label>
        <input type="text" name="team1_player1" required>

        <label for="team1_player2">Team 1 - Player 2:</label>
        <input type="text" name="team1_player2" required>

        <label for="team2_player1">Team 2 - Player 1:</label>
        <input type="text" name="team2_player1" required>

        <label for="team2_player2">Team 2 - Player 2:</label>
        <input type="text" name="team2_player2" required>

        <button type="submit">Create Match</button>
    </form>
@endsection
