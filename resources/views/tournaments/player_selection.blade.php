@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Add Players to Tournament: {{ $tournament->name }}</h3>

    <form action="{{ route('tournament.add-player', $tournament->id) }}" method="POST">
        @csrf
        <label for="player">Select Player:</label>
        <select name="player_id" class="form-control">
            @foreach($players as $player)
                <option value="{{ $player->id }}">{{ $player->username }} ({{ $player->email }})</option>
            @endforeach
        </select>

        <button type="submit" class="btn btn-primary mt-3">Add Player</button>
    </form>
</div>
@endsection
