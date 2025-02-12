@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Match</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input:<br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('matches.singles.update', $match->id) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="stage">Stage:</label>
            <select name="stage" id="stage" class="form-control" required>
                <option value="">Select Stage</option>
                @foreach($stages as $option)
                    <option value="{{ $option }}" {{ old('stage', $match->stage) == $option ? 'selected' : '' }}>
                        {{ $option }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="match_date">Match Date:</label>
            <input type="date" name="match_date" id="match_date" class="form-control" value="{{ old('match_date', $match->match_date) }}" required>
        </div>

        <div class="form-group">
            <label for="match_time">Match Time:</label>
            <input type="time" name="match_time" id="match_time" class="form-control" value="{{ old('match_time', date('H:i', strtotime($match->match_time))) }}" required>
        </div>

        <div class="form-group">
            <label for="set1_player1_points">Set 1 - Player 1 Points:</label>
            <input type="number" name="set1_player1_points" id="set1_player1_points" class="form-control" value="{{ old('set1_player1_points', $match->set1_player1_points) }}" required>
        </div>

        <div class="form-group">
            <label for="set1_player2_points">Set 1 - Player 2 Points:</label>
            <input type="number" name="set1_player2_points" id="set1_player2_points" class="form-control" value="{{ old('set1_player2_points', $match->set1_player2_points) }}" required>
        </div>

        <div class="form-group">
            <label for="set2_player1_points">Set 2 - Player 1 Points:</label>
            <input type="number" name="set2_player1_points" id="set2_player1_points" class="form-control" value="{{ old('set2_player1_points', $match->set2_player1_points) }}" required>
        </div>

        <div class="form-group">
            <label for="set2_player2_points">Set 2 - Player 2 Points:</label>
            <input type="number" name="set2_player2_points" id="set2_player2_points" class="form-control" value="{{ old('set2_player2_points', $match->set2_player2_points) }}" required>
        </div>

        <div class="form-group">
            <label for="set3_player1_points">Set 3 - Player 1 Points:</label>
            <input type="number" name="set3_player1_points" id="set3_player1_points" class="form-control" value="{{ old('set3_player1_points', $match->set3_player1_points) }}" required>
        </div>

        <div class="form-group">
            <label for="set3_player2_points">Set 3 - Player 2 Points:</label>
            <input type="number" name="set3_player2_points" id="set3_player2_points" class="form-control" value="{{ old('set3_player2_points', $match->set3_player2_points) }}" required>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Update Match</button>
    </form>
</div>
@endsection
