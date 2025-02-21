@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Manage Your Players</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Age</th>
                <th>Category</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($players as $player)
                <tr>
                    <td>{{ $player->id }}</td>
                    <td>{{ $player->name }}</td>
                    <td>{{ $player->age }}</td>
                    <td>{{ $player->category }}</td>
                    <td>
                        <a href="{{ route('player.edit', $player->id) }}" class="btn btn-primary btn-sm">Edit</a>
                        <button class="btn btn-danger btn-sm delete-player" data-id="{{ $player->id }}">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
