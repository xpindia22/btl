@extends('layouts.app')

@section('title', 'Create Singles Match')

@section('content')
    <h2>Create Singles Match</h2>
    <form method="POST" action="{{ route('matches.store') }}">
        @csrf
        <label for="player1">Player 1:</label>
        <input type="text" name="player1" required>

        <label for="player2">Player 2:</label>
        <input type="text" name="player2" required>

        <button type="submit">Create Match</button>
    </form>
@endsection
