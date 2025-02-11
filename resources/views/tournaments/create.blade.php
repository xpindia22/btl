@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create Tournament</h1>

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

    <form method="POST" action="{{ route('tournaments.add') }}">
        @csrf
        <div class="form-group">
            <label for="tournament_name">Tournament Name:</label>
            <input type="text" name="tournament_name" id="tournament_name" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Create Tournament</button>
    </form>
</div>
@endsection
