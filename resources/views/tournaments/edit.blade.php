@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="text-center">Manage Tournaments</h3>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tournament Name</th>
                <th>Created By</th>
                <th>Moderators</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tournaments as $tournament)
                <tr>
                    <form method="POST" action="{{ route('tournaments.update', $tournament->tournament_id) }}">
                        @csrf
                        @method('PUT')

                        <td>{{ $tournament->tournament_id }}</td>
                        <td>
                            <input type="text" name="name" value="{{ $tournament->tournament_name }}" class="form-control" required>
                        </td>
                        <td>{{ $tournament->created_by }}</td>
                        <td>
                            <select name="moderators[]" class="form-control" multiple>
                                @foreach ($allModerators as $moderator)
                                    <option value="{{ $moderator->id }}" 
                                        {{ in_array($moderator->id, explode(', ', $tournament->moderators ?? '')) ? 'selected' : '' }}>
                                        {{ $moderator->username }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <button type="submit" class="btn btn-primary">Update</button>
                            <form method="POST" action="{{ route('tournaments.destroy', $tournament->tournament_id) }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </form>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
