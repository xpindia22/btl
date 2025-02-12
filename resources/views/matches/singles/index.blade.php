@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Singles Matches</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Filter Form -->
    <form method="GET" action="{{ route('matches.singles.index') }}">
        <label for="tournament_id">Tournament:</label>
        <select name="tournament_id" id="tournament_id">
            <option value="">All Tournaments</option>
            @foreach($tournaments as $tournament)
                <option value="{{ $tournament->id }}" {{ request('tournament_id') == $tournament->id ? 'selected' : '' }}>
                    {{ $tournament->name }}
                </option>
            @endforeach
        </select>

        <label for="player_id">Player:</label>
        <select name="player_id" id="player_id">
            <option value="">All Players</option>
            @foreach($players as $player)
                <option value="{{ $player->id }}" {{ request('player_id') == $player->id ? 'selected' : '' }}>
                    {{ $player->name }}
                </option>
            @endforeach
        </select>

        <label for="match_date">Match Date:</label>
        <select name="match_date" id="match_date">
            <option value="">All Dates</option>
            @foreach($dates as $date)
                <option value="{{ $date->match_date }}" {{ request('match_date') == $date->match_date ? 'selected' : '' }}>
                    {{ date("d-m-Y", strtotime($date->match_date)) }}
                </option>
            @endforeach
        </select>

        <label for="datetime">Match Time:</label>
        <select name="datetime" id="datetime">
            <option value="">All Times</option>
            @foreach($datetimes as $dt)
                <option value="{{ $dt->match_time }}" {{ request('datetime') == $dt->match_time ? 'selected' : '' }}>
                    {{ date("h:i A", strtotime($dt->match_time)) }}
                </option>
            @endforeach
        </select>

        <button type="submit">Filter</button>
    </form>

    @if($matches->count() > 0)
    <table class="table">
        <thead>
            <tr>
                <th>Match ID</th>
                <th>Tournament</th>
                <th>Category</th>
                <th>Player 1</th>
                <th>Player 2</th>
                <th>Stage</th>
                <th>Match Date</th>
                <th>Match Time</th>
                <th>Set 1</th>
                <th>Set 2</th>
                <th>Set 3</th>
                <th>Winner</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($matches as $match)
                @php
                    // Calculate total points for players.
                    $p1_total = $match->set1_player1_points + $match->set2_player1_points + $match->set3_player1_points;
                    $p2_total = $match->set1_player2_points + $match->set2_player2_points + $match->set3_player2_points;
                    $winner = $p1_total > $p2_total ? optional($match->player1)->name : ($p1_total < $p2_total ? optional($match->player2)->name : 'Draw');

                    // Check if the user can edit or delete the match.
                    $canEditOrDelete = Auth::user()->is_admin 
                        || ($match->created_by == Auth::id()) 
                        || (optional($match->tournament)->moderators->contains('id', Auth::id()));
                @endphp
                <tr>
                    <td>{{ $match->id }}</td>
                    <td>{{ optional($match->tournament)->name ?? 'N/A' }}</td>
                    <td>{{ optional($match->category)->name ?? 'N/A' }}</td>
                    <td>{{ optional($match->player1)->name ?? 'N/A' }}</td>
                    <td>{{ optional($match->player2)->name ?? 'N/A' }}</td>
                    <td>{{ $match->stage }}</td>
                    <td>{{ $match->match_date ? date("d-m-Y", strtotime($match->match_date)) : 'N/A' }}</td>
                    <td>{{ $match->match_time ? date("h:i A", strtotime($match->match_time)) : 'N/A' }}</td>
                    <td>{{ $match->set1_player1_points }} - {{ $match->set1_player2_points }}</td>
                    <td>{{ $match->set2_player1_points }} - {{ $match->set2_player2_points }}</td>
                    <td>{{ $match->set3_player1_points }} - {{ $match->set3_player2_points }}</td>
                    <td>{{ $winner }}</td>
                    <td>
                        @if($canEditOrDelete)
                            <a href="{{ route('matches.singles.edit', $match->id) }}">Edit</a> |
                            <form action="{{ route('matches.singles.destroy', $match->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this match?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background:none;border:none;color:red;">Delete</button>
                            </form>
                        @else
                            N/A
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @else
        <p>No matches found for the selected filters.</p>
    @endif
</div>
@endsection
