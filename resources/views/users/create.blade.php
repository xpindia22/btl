@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Register</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('users.create') }}" class="registration-form mb-5">
        @csrf
        <div class="mb-3">
            <label>Username:</label>
            <input type="text" name="username" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Email:</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Password:</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Confirm Password:</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Date of Birth:</label>
            <input type="date" name="dob" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Sex:</label>
            <select name="sex" class="form-control" required>
                <option value="Male" {{ old('sex') == 'Male' ? 'selected' : '' }}>Male</option>
                <option value="Female" {{ old('sex') == 'Female' ? 'selected' : '' }}>Female</option>
                <option value="Other" {{ old('sex') == 'Other' ? 'selected' : '' }}>Other</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Mobile No:</label>
            <input type="text" name="mobile_no" class="form-control">
        </div>

        <div class="mb-3">
            <label>Role:</label>
            <select name="role" class="form-control" required>
                @if(Auth::check() && Auth::user()->role === 'admin')
                    <option value="admin">Admin</option>
                @endif
                <option value="user">User</option>
                <option value="visitor">Visitor</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Register</button>
    </form>
</div>
@endsection
