@extends('layouts.app')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
@endsection

@section('content')
<div class="login-wrapper">
    <div class="login-box">
        <h3 class="text-center mb-3">Reset Password</h3>

        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="input-group">
                <label for="email">Email Address</label>
                <input id="email" type="email" 
                       class="@error('email') is-invalid @enderror" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required autocomplete="email" autofocus>
            </div>

            @error('email')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror

            <button type="submit" class="login-button">Send Password Reset Link</button>

            <div class="link-row">
                <a href="{{ route('login') }}">Back to Login</a>
            </div>
        </form>
    </div>
</div>
@endsection
