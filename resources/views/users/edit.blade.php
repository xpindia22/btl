@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Manage Users</h2>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Mobile No</th>
                <th>Role</th>
                <th>Moderator</th> <!-- ✅ Editable -->
                <th>Creator</th> <!-- ✅ Editable -->
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

                    <!-- ✅ Editable "Moderator Of" Column -->
                    <td>
                        <select name="moderated_tournaments[]" class="form-control" multiple>
                            @foreach ($tournaments as $tournament)
                                <option value="{{ $tournament->id }}"
                                    {{ in_array($tournament->id, $user->moderatedTournaments->pluck('id')->toArray()) ? 'selected' : '' }}>
                                    {{ $tournament->name }} ({{ $tournament->year }})
                                </option>
                            @endforeach
                        </select>
                    </td>

                    <!-- ✅ Editable "Creator Of" Column -->
                    <td>
                        <select name="created_tournaments[]" class="form-control" multiple>
                            @foreach ($tournaments as $tournament)
                                <option value="{{ $tournament->id }}"
                                    {{ in_array($tournament->id, $user->createdTournaments->pluck('id')->toArray()) ? 'selected' : '' }}>
                                    {{ $tournament->name }} ({{ $tournament->year }})
                                </option>
                            @endforeach
                        </select>
                    </td>

                    <td>
                        <button type="submit" class="btn btn-success btn-sm">Update</button>
                        </form> <!-- ✅ Properly closed form -->

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

    <!-- ✅ Centered Pagination -->
    <div class="d-flex justify-content-center">
        {{ $users->appends(request()->query())->links('vendor.pagination.default') }}
    </div>

    <!-- ✅ Button to Create a New User -->
    <a href="{{ route('users.create') }}" class="btn btn-success mt-3">Create New User</a>

</div>
@endsection
