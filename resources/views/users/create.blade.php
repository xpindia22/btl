@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Create New User</h2>

    <form action="{{ route('users.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" name="username" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm Password:</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="mobile_no">Mobile No:</label>
            <input type="text" name="mobile_no" class="form-control">
        </div>

        <div class="form-group">
            <label for="role">Role:</label>
            <select name="role" class="form-control" required>
                <option value="admin">Admin</option>
                <option value="user">User</option>
                <option value="visitor">Visitor</option>
            </select>
        </div>

        <!-- ✅ Ensure created_by is stored properly -->
        <input type="hidden" name="created_by" value="{{ auth()->id() }}">

        <button type="submit" class="btn btn-primary mt-3">Create User</button>
    </form>

    <a href="{{ route('users.index') }}" class="btn btn-secondary mt-3">Back to Users</a>

</div>
@endsection
