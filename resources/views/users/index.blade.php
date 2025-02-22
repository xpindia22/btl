@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Registered Users</h2>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Mobile No</th>
                <th>Role</th>
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
                        <select name="role">
                            <option value="visitor" {{ $user->role === 'visitor' ? 'selected' : '' }}>Visitor</option>
                            <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </td>
                    <td>
                        <button type="submit" class="btn btn-success btn-sm">Update</button>
                        </form> <!-- ✅ Properly closed form -->

                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm">Edit</a>

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

    <!-- ✅ Button to Create a New User -->
    <a href="{{ route('users.create') }}" class="btn btn-success mt-3">Create New User</a>

</div>

@endsection

 