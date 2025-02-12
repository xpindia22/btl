@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Match</h1>

    <form method="POST" action="{{ route('matches.singles.update', $match->id) }}">
        @csrf
        @method('PUT')

        <label for="stage">Stage:</label>
        <select name="stage" id="stage" required>
            <option value="">Select Stage</option>
            @foreach($stages as $option)
                <option value="{{ $option }}" {{ $match->stage == $option ? 'selected' : '' }}>
                    {{ $option }}
                </option>
            @endforeach
        </select>
        <br>

        <label for="match_date">Match Date:</label>
        <input type="date" name="match_date" id="match_date" value="{{ $match->match_date }}" required>
        <br>

        <label for="match_time">Match Time:</label>
        <input type="time" name="match_time" id="match_time" value="{{ date('H:i', strtotime($match->match_time)) }}" required>
        <br>

        <label for="set1_player1_points">Set 1 - Player 1 Points:</label>
        <input type="number" name="set1_player1_points" id="set1_player1_points" value="{{ $match->set1_player1_points }}" required>
        <br>

        <label for="set1_player2_points">Set 1 - Player 2 Points:</label>
        <input type="number" name="set1_player2_points" id="set1_player2_points" value="{{ $match->set1_player2_points }}" required>
        <br>

        <label for="set2_player1_points">Set 2 - Player 1 Points:</label>
        <input type="number" name="set2_player1_points" id="set2_player1_points" value="{{ $match->set2_player1_points }}" required>
        <br>

        <label for="set2_player2_points">Set 2 - Player 2 Points:</label>
        <input type="number" name="set2_player2_points" id="set2_player2_points" value="{{ $match->set2_player2_points }}" required>
        <br>

        <label for="set3_player1_points">Set 3 - Player 1 Points:</label>
        <input type="number" name="set3_player1_points" id="set3_player1_points" value="{{ $match->set3_player1_points }}" required>
        <br>

        <label for="set3_player2_points">Set 3 - Player 2 Points:</label>
        <input type="number" name="set3_player2_points" id="set3_player2_points" value="{{ $match->set3_player2_points }}" required>
        <br>

        <button type="submit">Update Match</button>
    </form>
</div>
@endsection
