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
                    <form method="POST" action="{{ route('users.update', ['id' => $user->id]) }}">
                        @csrf
                        @method('PUT')
                        <td>{{ $user->id }}</td>
                        <td><input type="text" name="username" value="{{ $user->username }}" required></td>
                        <td><input type="email" name="email" value="{{ $user->email }}" required></td>
                        <td><input type="text" name="mobile_no" value="{{ $user->mobile_no }}"></td>
                        <td>
                            <select name="Role">
                                <option value="admin" {{ $user->Role === 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="user" {{ $user->Role === 'user' ? 'selected' : '' }}>User</option>
                                <option value="visitor" {{ $user->Role === 'visitor' ? 'selected' : '' }}>Visitor</option>
                            </select>
                        </td>
                        <td>
                            <button type="submit" class="btn btn-success btn-sm">Update</button>
                            <a href="{{ route('users.edit', ['id' => $user->id]) }}" class="btn btn-primary btn-sm">Edit</a>
                            <form action="{{ route('users.destroy', ['id' => $user->id]) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </form>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
