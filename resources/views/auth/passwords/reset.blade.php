@extends('layouts.app')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
@endsection

@section('content')
<div class="login-wrapper">
    <div class="login-box">
        <h3 class="text-center mb-3">Reset Password</h3>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="input-group">
                <label for="email">Email Address</label>
                <input id="email" type="email" 
                       class="@error('email') is-invalid @enderror" 
                       name="email" 
                       value="{{ $email ?? old('email') }}" 
                       required autocomplete="email" autofocus>
            </div>

            @error('email')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror

            <div class="input-group">
                <label for="password">Password</label>
                <input id="password" type="password" 
                       class="@error('password') is-invalid @enderror" 
                       name="password" 
                       required autocomplete="new-password">
            </div>

            @error('password')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror

            <div class="input-group">
                <label for="password-confirm">Confirm Password</label>
                <input id="password-confirm" type="password" 
                       name="password_confirmation" 
                       required autocomplete="new-password">
            </div>

            <button type="submit" class="login-button">Reset Password</button>

            <div class="link-row">
                <a href="{{ route('login') }}">Back to Login</a>
            </div>
        </form>
    </div>
</div>
@endsection
