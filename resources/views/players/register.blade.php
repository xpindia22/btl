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
            <label for="email">Primary Email:</label>
            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" 
                   value="{{ old('email') }}" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="secondary_email">Secondary Email:</label>
            <input type="email" name="secondary_email" id="secondary_email" 
                   class="form-control @error('secondary_email') is-invalid @enderror" 
                   value="{{ old('secondary_email') }}">
            @error('secondary_email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- ✅ Fixed Password and Password Confirmation --}}
        <div class="form-group mb-3">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" 
                   class="form-control @error('password') is-invalid @enderror" required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="password_confirmation">Confirm Password:</label>
            <input type="password" name="password_confirmation" id="password_confirmation" 
                   class="form-control @error('password_confirmation') is-invalid @enderror" required>
            @error('password_confirmation')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- ✅ Security Questions for Password Recovery --}}
        <h4 class="mt-4">Security Questions</h4>

        <div class="form-group mb-3">
            <label for="secret_question1">Your Pet's Name:</label>
            <input type="text" name="secret_question1" id="secret_question1" 
                   class="form-control @error('secret_question1') is-invalid @enderror" 
                   value="{{ old('secret_question1') }}" required>
            @error('secret_question1')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="secret_question2">Your Favorite Color:</label>
            <input type="text" name="secret_question2" id="secret_question2" 
                   class="form-control @error('secret_question2') is-invalid @enderror" 
                   value="{{ old('secret_question2') }}" required>
            @error('secret_question2')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="secret_question3">Your Favorite Food:</label>
            <input type="text" name="secret_question3" id="secret_question3" 
                   class="form-control @error('secret_question3') is-invalid @enderror" 
                   value="{{ old('secret_question3') }}" required>
            @error('secret_question3')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- ✅ Simple CAPTCHA for Anti-Bot --}}
        <div class="form-group mb-3">
            <label for="captcha">What is 4 + 6?</label>
            <input type="text" name="captcha" id="captcha" class="form-control @error('captcha') is-invalid @enderror" required>
            @error('captcha')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Register</button>
    </form>
</div>
@endsection
