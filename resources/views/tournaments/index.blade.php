@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="text-center">Tournaments List</h3>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('tournaments.create') }}" class="btn btn-success mb-3">Add Tournament</a>
    <a href="{{ route('tournaments.edit') }}" class="btn btn-primary mb-3">Manage Tournaments</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Created By</th>
                <th>Moderators</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tournaments as $tournament)
                <tr>
                    <td>{{ $tournament->tournament_id }}</td>
                    <td>{{ $tournament->tournament_name }}</td>
                    <td>{{ $tournament->created_by }}</td>
                    <td>{{ $tournament->moderators ?? 'None' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $tournaments->links() }}
</div>
@endsection
