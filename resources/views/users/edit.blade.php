@extends('layouts.app')

@section('content')
<div class="container">
    <h2>--- Manage Users --- <a href="{{ route('users.index') }}">View Users</a></h2>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Mobile No</th>
                <th>Role</th>
                <th>Moderator</th> <!-- ✅ Editable with Checkboxes -->
                <th>Creator</th> <!-- ✅ Editable with Checkboxes -->
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>
                        <form method="POST" action="{{ route('users.update', $user->id) }}">
                            @csrf
                            @method('PUT')
                            <input type="text" name="username" value="{{ $user->username }}" required>
                    </td>
                    <td><input type="email" name="email" value="{{ $user->email }}" required></td>
                    <td><input type="text" name="mobile_no" value="{{ $user->mobile_no }}"></td>
                    <td>
                        <select name="role" class="form-control">
                            <option value="visitor" {{ $user->role === 'visitor' ? 'selected' : '' }}>Visitor</option>
                            <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </td>

                    <td>
                        @foreach ($tournaments as $tournament)
                            <label>
                                <input type="checkbox" name="moderated_tournaments[]" value="{{ $tournament->id }}"
                                    {{ in_array($tournament->id, $user->moderatedTournaments->pluck('id')->toArray()) ? 'checked' : '' }}>
                                {{ $tournament->name }} ({{ $tournament->year }})
                            </label><br>
                        @endforeach
                    </td>

                    <td>
                        @foreach ($tournaments as $tournament)
                            <label>
                                <input type="checkbox" name="created_tournaments[]" value="{{ $tournament->id }}"
                                    {{ in_array($tournament->id, $user->createdTournaments->pluck('id')->toArray()) ? 'checked' : '' }}>
                                {{ $tournament->name }} ({{ $tournament->year }})
                            </label><br>
                        @endforeach

                        @if ($user->createdTournaments->isEmpty())
                            <span class="text-danger">xxx (admin)</span> <!-- ✅ Shows Default Admin -->
                        @endif
                    </td>

                    <td>
                        <button type="submit" class="btn btn-success btn-sm">Update</button>
                        </form> 

                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-center">
        {{ $users->links('vendor.pagination.default') }}
    </div>
</div>
@endsection
