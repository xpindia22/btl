@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Girls Doubles Match Results</h1>
    @if(session('message'))
        <p>{{ session('message') }}</p>
    @endif
    @if($matches->count())
    <div class="girls-doubles-columns">
        <table border="1" cellspacing="0" cellpadding="5">
            <tr>
                <th>Match ID</th>
                <th>Tournament</th>
                <th>Category</th>
                <th>Team 1</th>
                <th>Team 2</th>
                <th>Stage</th>
                <th>Match Date</th>
                <th>Match Time</th>
                <th>Set 1 (Team 1 - Team 2)</th>
                <th>Set 2 (Team 1 - Team 2)</th>
                <th>Set 3 (Team 1 - Team 2)</th>
                <th>Winner</th>
                <th>Actions</th>
            </tr>
            @foreach($matches as $match)
                @php
                    $team1_points = $match->set1_team1_points + $match->set2_team1_points + $match->set3_team1_points;
                    $team2_points = $match->set1_team2_points + $match->set2_team2_points + $match->set3_team2_points;
                    $overall_winner = $team1_points > $team2_points ? 'Team 1' : ($team1_points < $team2_points ? 'Team 2' : 'Draw');
                @endphp
                <tr>
                    <td>{{ $match->match_id }}</td>
                    <td>{{ $match->tournament_name }}</td>
                    <td>{{ $match->category_name }}</td>
                    <td>{{ $match->team1_player1_name }} &amp; {{ $match->team1_player2_name }}</td>
                    <td>{{ $match->team2_player1_name }} &amp; {{ $match->team2_player2_name }}</td>
                    <td>
                        @can('update', $match)
                        <form method="POST" action="{{ route('matches.doubles_girls.update', $match->match_id) }}">
                            @csrf
                            @method('PUT')
                            <select name="stage">
                                @foreach($stages as $stage)
                                    <option value="{{ $stage }}" {{ $match->stage === $stage ? 'selected' : '' }}>
                                        {{ $stage }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            {{ $match->stage }}
                        @endcan
                    </td>
                    <td>
                        @can('update', $match)
                            <input type="date" name="match_date" value="{{ $match->match_date }}">
                        @else
                            {{ $match->match_date }}
                        @endcan
                    </td>
                    <td>
                        @can('update', $match)
                            <input type="time" name="match_time" value="{{ $match->match_time }}">
                        @else
                            {{ $match->match_time }}
                        @endcan
                    </td>
                    <td>
                        @can('update', $match)
                            <input type="number" name="set1_team1_points" value="{{ $match->set1_team1_points }}" style="width: 50px;"> -
                            <input type="number" name="set1_team2_points" value="{{ $match->set1_team2_points }}" style="width: 50px;">
                        @else
                            {{ $match->set1_team1_points }} - {{ $match->set1_team2_points }}
                        @endcan
                    </td>
                    <td>
                        @can('update', $match)
                            <input type="number" name="set2_team1_points" value="{{ $match->set2_team1_points }}" style="width: 50px;"> -
                            <input type="number" name="set2_team2_points" value="{{ $match->set2_team2_points }}" style="width: 50px;">
                        @else
                            {{ $match->set2_team1_points }} - {{ $match->set2_team2_points }}
                        @endcan
                    </td>
                    <td>
                        @can('update', $match)
                            <input type="number" name="set3_team1_points" value="{{ $match->set3_team1_points }}" style="width: 50px;"> -
                            <input type="number" name="set3_team2_points" value="{{ $match->set3_team2_points }}" style="width: 50px;">
                        @else
                            {{ $match->set3_team1_points }} - {{ $match->set3_team2_points }}
                        @endcan
                    </td>
                    <td>{{ $overall_winner }}</td>
                    <td>
                        @can('update', $match)
                            <button type="submit" name="edit_match">Edit</button>
                        </form>
                        @endcan
                        
                        @can('delete', $match)
                        <form method="POST" action="{{ route('matches.doubles_girls.destroy', $match->match_id) }}" onsubmit="return confirm('Are you sure you want to delete this match?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" name="delete_match">Delete</button>
                        </form>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
    @else
        <p>No matches found.</p>
    @endif
</div>
@endsection
