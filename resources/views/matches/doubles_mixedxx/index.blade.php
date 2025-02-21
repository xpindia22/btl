@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Mixed Doubles Matches</h1>

    @if(session('message'))
        <p class="alert alert-success">{{ session('message') }}</p>
    @endif

    @if($matches->count() > 0)
    <table class="table table-bordered table-responsive">
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
            </tr>
        </thead>
        <tbody>
            @foreach($matches as $match)
                <tr>
                    <td>{{ $match->id }}</td>
                    <td>{{ $match->tournament->name }}</td>
                    <td>{{ $match->category->name }}</td>
                    <td>{{ $match->team1Player1->name }} & {{ $match->team1Player2->name }}</td>
                    <td>{{ $match->team2Player1->name }} & {{ $match->team2Player2->name }}</td>
                    <td>{{ $match->stage }}</td>
                    <td>{{ $match->match_date }}</td>
                    <td>{{ \Carbon\Carbon::parse($match->match_time)->format('H:i') }}</td>
                    <td>{{ $match->set1_team1_points ?? 0 }} - {{ $match->set1_team2_points ?? 0 }}</td>
                    <td>{{ $match->set2_team1_points ?? 0 }} - {{ $match->set2_team2_points ?? 0 }}</td>
                    <td>{{ $match->set3_team1_points ?? 0 }} - {{ $match->set3_team2_points ?? 0 }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @else
        <p>No matches found.</p>
    @endif
</div>
@endsection
