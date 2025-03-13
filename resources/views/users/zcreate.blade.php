@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Create New User</h2>
    <div class="form-container">
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
                <label for="dob">Date of Birth:</label>
                <input type="date" name="dob" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="sex">Sex:</label>
                <select name="sex" class="form-control" required>
                    <option value="Male" {{ old('sex') == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ old('sex') == 'Female' ? 'selected' : '' }}>Female</option>
                    <option value="Other" {{ old('sex') == 'Other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <div class="form-group">
                <label for="mobile_no">Mobile No:</label>
                <input type="text" name="mobile_no" class="form-control">
            </div>

            <div class="form-group">
                <label for="role">Role:</label>
                <select name="role" class="form-control" required>
                    @if(auth()->user() && auth()->user()->role === 'admin')
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    @endif
                    <option value="user" {{ old('role', 'user') == 'user' ? 'selected' : '' }}>User</option>
                    <option value="visitor" {{ old('role') == 'visitor' ? 'selected' : '' }}>Visitor</option>
                </select>
            </div>

            <!-- ✅ Hidden Field to Store Created By (Logged-in User ID) -->
            <input type="hidden" name="created_by" value="{{ auth()->id() }}">

            <button type="submit" class="btn btn-primary mt-3">Create User</button>
        </form>

        <a href="{{ route('users.index') }}" class="btn btn-secondary mt-3">Back to Users</a>
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Center the form container */
    .form-container {
        max-width: 450px;
        margin: 0 auto;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #f9f9f9;
    }
    /* Use flexbox to align label and input on the same line */
    .form-group {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 15px;
    }
    .form-group label {
        width: 160px;           /* Fixed width for labels */
        text-align: right;
        margin-right: 10px;
    }
    .form-group .form-control {
        width: 250px;           /* Fixed width for input fields */
    }
</style>
@endsection
