@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Profile</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form action="{{ route('profile.update') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" value="{{ $user->username }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" value="{{ $user->email }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Age</label>
            <input type="number" name="age" value="{{ $user->age }}" class="form-control">
        </div>
        <div class="mb-3">
            <label>Sex</label>
            <select name="sex" class="form-control">
                <option value="Male" {{ $user->sex == 'Male' ? 'selected' : '' }}>Male</option>
                <option value="Female" {{ $user->sex == 'Female' ? 'selected' : '' }}>Female</option>
                <option value="Other" {{ $user->sex == 'Other' ? 'selected' : '' }}>Other</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
</div>
@endsection
