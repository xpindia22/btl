@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Matches</h1>
    <a href="{{ route('matches.create') }}" class="btn btn-primary">Create New Match</a>

    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Tournament</th>
                <th>Category</th>
                <th>Players</th>
                <th>Score</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($matches as $match)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $match->tournament->name ?? 'N/A' }}</td>
                    <td>{{ $match->category->name ?? 'N/A' }}</td>
                    <td>{{ $match->player1->name ?? 'N/A' }} vs {{ $match->player2->name ?? 'N/A' }}</td>
                    <td>{{ $match->score ?? 'Not Set' }}</td>
                    <td>
                        <a href="#" class="btn btn-info">View</a>
                        <a href="#" class="btn btn-warning">Edit</a>
                        <a href="#" class="btn btn-danger">Delete</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
