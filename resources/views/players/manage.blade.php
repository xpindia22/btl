{{-- resources/views/players/manage.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Manage Players</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>UID</th>
                <th>Name</th>
                <th>Date of Birth</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($players as $player)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $player->uid }}</td>
                    <td>{{ $player->name }}</td>
                    <td>{{ $player->dob }}</td>
                    <td>{{ $player->age }}</td>
                    <td>{{ $player->sex }}</td>
                    <td>
                        <a href="{{ route('players.edit', $player->id) }}" class="btn btn-primary">Edit</a>
                        <form action="{{ route('players.destroy', $player->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
