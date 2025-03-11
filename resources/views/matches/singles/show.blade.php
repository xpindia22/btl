@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Singles Match Details</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Match ID</th>
                    <th>Tournament</th>
                    <th>Category</th>
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
                    <td>{{ $match->tournament->name }}</td>
                    <td>{{ $match->category->name }}</td>
                    <td>{{ $match->stage }}</td>
                    <td>{{ $match->match_date }}</td>
                    <td>{{ $match->match_time }}</td>
                    <td>{{ $match->set1_player1_points ?? '0' }} - {{ $match->set1_player2_points ?? '0' }}</td>
                    <td>{{ $match->set2_player1_points ?? '0' }} - {{ $match->set2_player2_points ?? '0' }}</td>
                    <td>{{ $match->set3_player1_points ?? '0' }} - {{ $match->set3_player2_points ?? '0' }}</td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
