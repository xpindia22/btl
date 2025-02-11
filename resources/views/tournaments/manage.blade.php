@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Manage Tournaments</h1>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Button to Create New Tournament --}}
    <a href="{{ route('tournaments.create') }}" class="btn btn-primary">Create New Tournament</a>
    <hr>

    {{-- Table of Tournaments --}}
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Categories</th>
                <th>Moderators</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @php
                $currentUser = Auth::user();
            @endphp
            @foreach($tournaments as $tournament)
                <tr>
                    <td>{{ $tournament->tournament_id }}</td>
                    <td>{{ $tournament->tournament_name }}</td>
                    <td>{{ $tournament->categories }}</td>
                    <td>{{ $tournament->moderators }}</td>
                    <td>
                        @if($currentUser->role == 'admin' || $tournament->created_by == $currentUser->id)
                            <a href="{{ route('tournaments.edit', $tournament->tournament_id) }}" class="btn btn-primary btn-sm">Edit</a>
                            <form action="{{ route('tournaments.destroy', $tournament->tournament_id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this tournament?')">Delete</button>
                            </form>
                        @else
                            <span>N/A</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
