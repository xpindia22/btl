@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Insert Singles Match</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> Please fix the following errors:
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Tournament Lock/Unlock --}}
    <form method="POST" action="{{ route('matches.singles.lockTournament') }}">
        @csrf
        <label for="tournament_id">Select Tournament:</label>
        <select name="tournament_id" {{ $lockedTournament ? 'disabled' : '' }} required>
            <option value="">Select Tournament</option>
            @foreach($tournaments as $t)
                <option value="{{ $t->id }}" {{ $lockedTournament && $lockedTournament->id == $t->id ? 'selected' : '' }}>
                    {{ $t->name }}
                </option>
            @endforeach
        </select>

        @if($lockedTournament)
            <button type="submit" formaction="{{ route('matches.singles.unlockTournament') }}" class="btn btn-danger">Unlock Tournament</button>
        @else
            <button type="submit" class="btn btn-primary">Lock Tournament</button>
        @endif
    </form>

    @if($lockedTournament)
    {{-- Create Match Form --}}
    <form method="POST" action="{{ route('matches.singles.store') }}">
        @csrf
        <input type="hidden" name="tournament_id" value="{{ $lockedTournament->id }}">

        <label for="category_id">Category:</label>
        <select name="category_id" required>
            <option value="">Select Category</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>

        <label for="player1_id">Player 1:</label>
        <select name="player1_id" required>
            <option value="">Select Player 1</option>
            @foreach($players as $p)
                <option value="{{ $p->id }}">{{ $p->name }}</option>
            @endforeach
        </select>

        <label for="player2_id">Player 2:</label>
        <select name="player2_id" required>
            <option value="">Select Player 2</option>
            @foreach($players as $p)
                <option value="{{ $p->id }}">{{ $p->name }}</option>
            @endforeach
        </select>

        <label for="stage">Stage:</label>
        <select name="stage" required>
            <option value="Pre Quarter Finals">Pre Quarter Finals</option>
            <option value="Quarter Finals">Quarter Finals</option>
            <option value="Semifinals">Semifinals</option>
            <option value="Finals">Finals</option>
        </select>

        <label for="date">Match Date:</label>
        <input type="date" name="date" required>

        <label for="match_time">Match Time (HH:MM):</label>
        <input type="time" name="match_time" required>

        <label>Set 1:</label>
        <input type="number" name="set1_player1_points" placeholder="P1 Score" required>
        <input type="number" name="set1_player2_points" placeholder="P2 Score" required>

        <label>Set 2:</label>
        <input type="number" name="set2_player1_points" placeholder="P1 Score" required>
        <input type="number" name="set2_player2_points" placeholder="P2 Score" required>

        <label>Set 3 (if needed):</label>
        <input type="number" name="set3_player1_points" placeholder="P1 Score">
        <input type="number" name="set3_player2_points" placeholder="P2 Score">

        <button type="submit" class="btn btn-success">Add Match</button>
    </form>
    @endif
</div>
@endsection
