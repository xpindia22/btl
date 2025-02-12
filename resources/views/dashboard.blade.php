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
                <h2>Manage All Matches</h2>
                <p>View, edit, or delete matches created by all users.</p>
                <a href="{{ route('matches.index') }}" class="btn btn-primary">View Matches</a>
                <div class="sub-links" style="margin-top: 10px;">
                    <a href="{{ route('singles.create') }}" class="btn btn-secondary">Create Singles</a>
                    <a href="{{ route('singles.edit') }}" class="btn btn-secondary">Edit Singles</a>
                    <a href="{{ route('doubles.create') }}" class="btn btn-secondary">Create Doubles</a>
                    <a href="{{ route('doubles.edit') }}" class="btn btn-secondary">Edit Doubles</a>
                    <a href="{{ route('mixed_doubles.create') }}" class="btn btn-secondary">Create Mixed Doubles</a>
                    <a href="{{ route('mixed_doubles.edit') }}" class="btn btn-secondary">Edit Mixed Doubles</a>
                </div>
            </div>
        @endif

        @if($is_user)
            <div class="card">
                <h2>Enter Matches</h2>
                <p>Enter match scores for your tournaments.</p>
                <a href="{{ route('matches.index') }}" class="btn btn-primary">Enter Matches</a>
            </div>
        @endif

        @if($is_player)
            <div class="card">
                <h2>View Tournament Results</h2>
                <p>Check results of different tournament categories.</p>
                <a href="{{ route('results.singles') }}" class="btn btn-primary">Singles Results</a>
                <a href="{{ route('results.doubles') }}" class="btn btn-primary">Doubles Results</a>
                <a href="{{ route('results.mixed_doubles') }}" class="btn btn-primary">Mixed Doubles Results</a>
            </div>
        @endif
    </div>
</div>
@endsection
