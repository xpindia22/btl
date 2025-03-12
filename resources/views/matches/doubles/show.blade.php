@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Doubles Match Details</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Match ID</th>
                <th>Tournament</th>
                <th>Category</th>
                <th>Team 1 Players</th>
                <th>Team 2 Players</th>
                <th>Stage</th>
                <th>Date</th>
                <th>Time</th>

                <th>Set 1</th>
                <th>Set 2</th>
                <th>Set 3</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $match->id }}</td>
                <td>{{ optional($match->tournament)->name }}</td>
                <td>{{ optional($match->category)->name }}</td>
                <td>
                    {{ optional($match->team1Player1)->name }}<br>
                    {{ optional($match->team1Player2)->name }}
                </td>
                <td>
                    {{ optional($match->team2Player1)->name }}<br>
                    {{ optional($match->team2Player2)->name }}
                </td>
                <td>{{ $match->stage }}</td>
                <td>{{ $match->match_date }}</td>
                <td>{{ $match->match_time }}</td>

                <td>{{ $match->set1_team1_points }} - {{ $match->set1_team2_points }}</td>
                <td>{{ $match->set2_team1_points }} - {{ $match->set2_team2_points }}</td>
                <td>{{ $match->set3_team1_points ?? 'N/A' }} - {{ $match->set3_team2_points ?? 'N/A' }}</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection
