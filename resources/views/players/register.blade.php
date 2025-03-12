@extends('layouts.app')

@section('content')
<div class="container my-5 p-4 shadow-sm bg-white rounded">
    <h1 class="mb-4">Player Registration</h1>

    {{-- Display Success or Info Messages --}}
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

    {{-- Display Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Registration Form --}}
    <form method="POST" action="{{ route('players.register.post') }}" class="registration-form mb-3">
        @csrf

        <div class="form-group mb-3">
            <label for="name">Full Name:</label>
            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                   value="{{ old('name') }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" 
                   value="{{ old('email') }}" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="mobile">Mobile Number:</label>
            <input type="text" name="mobile" id="mobile" class="form-control @error('mobile') is-invalid @enderror" 
                   value="{{ old('mobile') }}" required>
            @error('mobile')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="dob">Date of Birth:</label>
            <input type="date" name="dob" id="dob" class="form-control @error('dob') is-invalid @enderror" 
                   value="{{ old('dob') }}" required>
            @error('dob')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="sex">Gender:</label>
            <select name="sex" id="sex" class="form-select @error('sex') is-invalid @enderror" required>
                <option value="M" {{ old('sex') == 'M' ? 'selected' : '' }}>Male</option>
                <option value="F" {{ old('sex') == 'F' ? 'selected' : '' }}>Female</option>
            </select>
            @error('sex')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" 
                   class="form-control @error('password') is-invalid @enderror" required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Register</button>
    </form>
</div>
@endsection
