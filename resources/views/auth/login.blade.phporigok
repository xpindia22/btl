@extends('layouts.app')

@section('content')
<div class="login-wrapper">
    <div class="login-box">
        <h3 class="text-center mb-3">Welcome To BTL</h3>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email & Password in One Row -->
            <div class="input-row">
                <div class="input-group">
                    <label for="email">Email</label> 
                    <input type="email" name="email" required autofocus>
                </div>
                <div class="input-group">
                    <label for="password">Password</label> 
                    <input type="password" name="password" required>
                </div>
            </div>

            <!-- Remember Me -->
            <div class="remember-me">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember">Remember Me</label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="login-button">Login</button>

            <!-- Register Link -->
            <div class="register-link">
                <a href="{{ route('register') }}">Don't have an account? Register</a>
            </div>
        </form>
    </div>
</div>
@endsection
