@extends('layouts.app')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
 @endsection

@section('content')
<div class="login-wrapper">
    <div class="login-box">
        <h3 class="text-center mb-3">Welcome To BTL</h3>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email & Password in One Row -->
            <div class="input-row">
                <div class="input-group">
                    <label for="email">Email</label> 
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
                </div>
                <div class="input-group">
                    <label for="password">Password</label> 
                    <input type="password" id="password" name="password" required>
                </div>
            </div>

            <!-- Remember Me -->
            <div class="remember-me">
                <input type="hidden" name="remember" value="0"> <!-- ✅ Ensures Laravel always gets a value -->
                <input type="checkbox" name="remember" id="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember">Remember Me</label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="login-button">Login</button>

            
            <!-- Links in the same row -->
            <div class="link-row">
                <div class="forgot-password">
                    <a href="{{ route('password.request') }}">Forgot Your Password?</a>
                </div>
                <div class="register-link">
                    <a href="{{ route('users.create') }}">Don't have an account? Register</a>
                </div>
            </div>



        </form>
    </div>
</div>
@endsection
