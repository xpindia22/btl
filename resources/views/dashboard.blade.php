@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Welcome, {{ auth()->user()->username }}!</h1>
    <p>Your role: {{ auth()->user()->role }}</p>
    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        Logout
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
</div>
@endsection
