@extends('layouts.app')
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

@section('content')
<div class="container">
    <h1>Dashboard</h1>
    <p style="text-align: center;">Welcome, {{ $username }}!</p>

    <div class="card-container">
        
        @if($is_admin)
            <div class="card">
                <h2>Manage Users</h2>
                <p>View, edit, or delete user accounts.</p>
                <a href="{{ route('users.index') }}" class="btn btn-primary">Manage Users</a>
            </div>
            <div class="card">
                <h2>Manage All Tournaments</h2>
                <p>See all tournaments created by all users.</p>
                <a href="{{ route('tournaments.index') }}" class="btn btn-primary">View Tournaments</a>
            </div>
            <div class="card">
                <h2>Manage Categories</h2>
                <p>Edit or delete categories created by any user.</p>
                <a href="{{ route('categories.index') }}" class="btn btn-primary">Manage Categories</a>
            </div>
            <div class="card">
                <h2>Manage All Matches</h2>
                <p>View, edit, or delete matches created by all users.</p>
                <a href="{{ route('matches.index') }}" class="btn btn-primary">Manage Matches</a>
            </div>
            <div class="card">
                <h2>Manage Players</h2>
                <p>View all players and their details.</p>
                <a href="{{ route('players.index') }}" class="btn btn-primary">Manage Players</a>
            </div>
        @endif

        @if($is_user)
            <div class="card">
                <h2>Create Tournament</h2>
                <p>Create and manage your tournaments.</p>
                <a href="{{ route('tournaments.create') }}" class="btn btn-primary">Create Tournament</a>
            </div>
            <div class="card">
                <h2>Enter Matches</h2>
                <p>Enter match scores for your tournaments.</p>
                <a href="{{ route('matches.create') }}" class="btn btn-primary">Enter Matches</a>
            </div>
            <div class="card">
                <h2>View Your Players</h2>
                <p>Manage players you added to the system.</p>
                <a href="{{ route('players.index') }}" class="btn btn-primary">View Players</a>
            </div>
 
        @endif

        @if($is_player)
            <div class="card">
                <h2>View Tournament Results</h2>
                <p>Check results of different tournament categories.</p>
                <a href="{{ route('results.doubles') }}" class="btn btn-primary">Doubles Results</a>
                <a href="{{ route('results.mixed_doubles') }}" class="btn btn-primary">Mixed Doubles Results</a>
                <a href="{{ route('results.singles') }}" class="btn btn-primary">Singles Results</a>
            </div>
            <div class="card">
                <h2>View Rankings</h2>
                <p>Check rankings of players in singles and doubles.</p>
                <a href="{{ route('rankings.singles') }}" class="btn btn-primary">Singles Rankings</a>
                <a href="{{ route('rankings.doubles') }}" class="btn btn-primary">Doubles Rankings</a>
            </div>
            <div class="card">
                <h2>Player Profile</h2>
                <p>View your registered details and update them if needed.</p>
                <a href="{{ route('player.profile') }}" class="btn btn-primary">View Profile</a>
            </div>
        @endif

        <div class="card">
            <h2>View Results</h2>
            <p>Check tournament results and match standings.</p>
            <a href="{{ route('results.index') }}" class="btn btn-primary">View Results</a>
        </div>
    </div>
</div>
@endsection
