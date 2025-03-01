@extends('layouts.app')

@section('content')
<div class="container my-5 p-4 shadow-sm bg-white rounded">
    <h1 class="mb-4">Player Registration</h1>

    @if(session('message'))
        <div class="alert alert-info">
            {{ session('message') }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Registration Form --}}
    <form method="POST" action="{{ route('player.register') }}" class="registration-form mb-5">
        @csrf
        <div class="form-group">
            <label for="name">Full Name:</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
        </div>
        <div class="form-group">
            <label for="dob">Date of Birth:</label>
            <input type="date" name="dob" id="dob" class="form-control" value="{{ old('dob') }}" required>
        </div>
        <div class="form-group">
            <label for="sex">Gender:</label>
            <select name="sex" id="sex" class="form-select" required>
                <option value="M" {{ old('sex') == 'M' ? 'selected' : '' }}>Male</option>
                <option value="F" {{ old('sex') == 'F' ? 'selected' : '' }}>Female</option>
            </select>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Register</button>
    </form>
</div>
@endsection
