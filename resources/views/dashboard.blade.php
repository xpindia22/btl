@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Welcome, {{ Auth::user()->username ?? 'Guest' }}</h2>

    @if (Auth::check())
        <p>You are logged in as a {{ Auth::user()->role ?? 'User' }}.</p>

        @if (strtolower(Auth::user()->role) === 'admin')
            <div class="alert alert-info">Admin Dashboard</div>
        @elseif (strtolower(Auth::user()->role) === 'user')
            <div class="alert alert-success">User Dashboard</div>
        @else
            <div class="alert alert-warning">Visitor Dashboard</div>
        @endif

        <!-- ✅ Header Links with Dropdown -->
        <div class="mt-4">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <a class="navbar-brand" href="{{ route('dashboard') }}">Dashboard</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav">

                            <!-- Admin Links -->
                            @if (strtolower(Auth::user()->role) === 'admin')
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        Admin Panel
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="adminDropdown">
                                        <li><a class="dropdown-item" href="{{ route('users.index') }}">Manage Users</a></li>
                                        <li><a class="dropdown-item" href="{{ route('tournaments.index') }}">Manage Tournaments</a></li>
                                        <li><a class="dropdown-item" href="{{ route('categories.index') }}">Manage Categories</a></li>
                                    </ul>
                                </li>
                            @endif

                            <!-- Matches Links -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="matchesDropdown" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    Matches
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="matchesDropdown">
                                    <li><a class="dropdown-item" href="{{ route('matches.singles.index') }}">Singles Matches</a></li>
                                    <li><a class="dropdown-item" href="{{ route('matches.doubles.index') }}">Doubles Matches</a></li>
                                    @if (in_array(Auth::user()->role, ['admin', 'moderator', 'user']))
                                        <li><a class="dropdown-item" href="{{ route('matches.singles.create') }}">Add Singles Match</a></li>
                                        <li><a class="dropdown-item" href="{{ route('matches.doubles.create') }}">Add Doubles Match</a></li>
                                    @endif
                                </ul>
                            </li>

                            <!-- Results -->
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('results.index') }}">Match Results</a>
                            </li>

                            <!-- Logout -->
                            <li class="nav-item">
                                <a class="nav-link text-danger" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>

                        </ul>
                    </div>
                </div>
            </nav>
        </div>

    @else
        <p>Please <a href="{{ route('login') }}">log in</a> to access the dashboard.</p>
    @endif
</div>
@endsection
