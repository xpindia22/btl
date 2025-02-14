@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Mixed Doubles Matches</h1>
    @if(session('message'))
        <p class="message {{ strpos(session('message'), 'success') !== false ? 'success' : 'error' }}">
            {{ session('message') }}
        </p>
    @endif

    @if($matches->count() > 0)
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Match ID</th>
                <th>Tournament</th>
                <th>Category</th>
                <th>Team 1</th>
                <th>Team 2</th>
                <th>Stage</th>
                <th>Date</th>
                <th>Time</th>
                <th>Set 1 (Team1-Team2)</th>
                <th>Set 2 (Team1-Team2)</th>
                <th>Set 3 (Team1-Team2)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($matches as $match)
                @php
                    // Calculate total points for each team (if needed for further display)
                    $team1_points = $match->set1_team1_points + $match->set2_team1_points + $match->set3_team1_points;
                    $team2_points = $match->set1_team2_points + $match->set2_team2_points + $match->set3_team2_points;
                @endphp
                <tr>
                    <td>{{ $match->match_id }}</td>
                    <td>{{ $match->tournament_name }}</td>
                    <td>{{ $match->category_name }}</td>
                    <td>{{ $match->team1_player1_name }} &amp; {{ $match->team1_player2_name }}</td>
                    <td>{{ $match->team2_player1_name }} &amp; {{ $match->team2_player2_name }}</td>
                    <td>
                        <form method="POST" action="{{ route('matches.doubles_mixed.update', $match->match_id) }}">
                            @csrf
                            @method('PUT')
                            <select name="stage" class="form-control">
                                @foreach($stages as $stage)
                                    <option value="{{ $stage }}" {{ $match->stage == $stage ? 'selected' : '' }}>
                                        {{ $stage }}
                                    </option>
                                @endforeach
                            </select>
                    </td>
                    <td>
                        <input type="date" name="match_date" value="{{ $match->match_date }}" class="form-control">
                    </td>
                    <td>
                        <input type="time" name="match_time" value="{{ \Carbon\Carbon::parse($match->match_time)->format('H:i') }}" class="form-control">
                    </td>
                    <td>
                        <input type="number" name="set1_team1_points" value="{{ $match->set1_team1_points }}" class="form-control" style="width: 80px;">
                        <input type="number" name="set1_team2_points" value="{{ $match->set1_team2_points }}" class="form-control" style="width: 80px;">
                    </td>
                    <td>
                        <input type="number" name="set2_team1_points" value="{{ $match->set2_team1_points }}" class="form-control" style="width: 80px;">
                        <input type="number" name="set2_team2_points" value="{{ $match->set2_team2_points }}" class="form-control" style="width: 80px;">
                    </td>
                    <td>
                        <input type="number" name="set3_team1_points" value="{{ $match->set3_team1_points }}" class="form-control" style="width: 80px;">
                        <input type="number" name="set3_team2_points" value="{{ $match->set3_team2_points }}" class="form-control" style="width: 80px;">
                    </td>
                    <td>
                        <button type="submit" class="btn btn-primary btn-sm">Update</button>
                        </form>
                        <form method="POST" action="{{ route('matches.doubles_mixed.destroy', $match->match_id) }}" onsubmit="return confirm('Are you sure you want to delete this match?')" style="margin-top: 5px;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @else
        <p>No matches found.</p>
    @endif
</div>
@endsection
