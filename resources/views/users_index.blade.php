@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Users</h1>
    <a href="{{ route('users.create') }}" class="btn btn-primary">Register New User</a>
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
                    <form method="POST" action="{{ route('users.update') }}">
                        @csrf
                        <td>{{ $user->id }}</td>
                        <td><input type="text" name="username" value="{{ $user->username }}" required></td>
                        <td><input type="email" name="email" value="{{ $user->email }}" required></td>
                        <td><input type="text" name="mobile_no" value="{{ $user->mobile_no }}"></td>
                        <td>
                            <select name="role">
                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                                <option value="visitor" {{ $user->role === 'visitor' ? 'selected' : '' }}>Visitor</option>
                            </select>
                        </td>
                        <td>
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            <button type="submit" class="btn btn-success">Save</button>
                        </form>
                        <form method="POST" action="{{ route('users.destroy') }}" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                        </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
