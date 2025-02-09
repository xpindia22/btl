@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit User</h2>
    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" class="form-control" value="{{ $user->username }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
        </div>

        <div class="mb-3">
            <label for="Role" class="form-label">Role</label>
            <select name="Role" class="form-control">
                <option value="admin" {{ $user->Role === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="user" {{ $user->Role === 'user' ? 'selected' : '' }}>User</option>
                <option value="visitor" {{ $user->Role === 'visitor' ? 'selected' : '' }}>Visitor</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Update</button>
    </form>
</div>
@endsection
