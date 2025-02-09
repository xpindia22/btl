@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Match Results</h1>

    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Tournament</th>
                <th>Category</th>
                <th>Players</th>
                <th>Score</th>
            </tr>
        </thead>
        <tbody>
            @foreach($results as $result)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $result->tournament->name ?? 'N/A' }}</td>
                    <td>{{ $result->category->name ?? 'N/A' }}</td>
                    <td>{{ $result->player1->name ?? 'N/A' }} vs {{ $result->player2->name ?? 'N/A' }}</td>
                    <td>{{ $result->score }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
