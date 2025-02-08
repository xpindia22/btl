{{-- resources/views/auth/auth.blade.php --}}
@extends('layouts.app')
@section('content')
    <div class="container">
        <h2>Authentication</h2>
        @if(Auth::check())
            <p>Welcome, {{ Auth::user()->username }}!</p>
            @if(Auth::user()->role === 'admin')
                <p>You are an admin.</p>
            @elseif(Auth::user()->role === 'user' || Auth::user()->role === 'moderator')
                <p>You are a regular user.</p>
            @elseif(Auth::user()->role === 'player')
                <p>You are a player.</p>
            @else
                <p>You are a visitor.</p>
            @endif
            <a href="{{ route('logout') }}" class="btn btn-danger">Logout</a>
        @else
            <p>You are not logged in. Please <a href="{{ route('login') }}">login</a>.</p>
        @endif
    </div>
@endsection
