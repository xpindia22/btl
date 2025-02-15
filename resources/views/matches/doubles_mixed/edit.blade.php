@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Mixed Doubles Matches</h1>

    @if(session('message'))
        <p class="alert alert-success">{{ session('message') }}</p>
    @endif

    @if($matches->count() > 0)
    <table class="table table-bordered table-responsive">
        <thead>
            <tr>
                <th>Match ID</th>
                <th>Tournament</th>
                <th>Category</th>
                <th>Team 1</th>
                <th>Team 2</th>
                <th>Stage</th>
                <th>Date</th>
                <th>Time</th>
                <th>Set 1 (Team1-Team2)</th>
                <th>Set 2 (Team1-Team2)</th>
                <th>Set 3 (Team1-Team2)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($matches as $match)
                <tr>
                    <td>{{ $match->id }}</td>
                    <td>{{ $match->tournament->name }}</td>
                    <td>{{ $match->category->name }}</td>
                    <td>{{ $match->team1Player1->name }} & {{ $match->team1Player2->name }}</td>
                    <td>{{ $match->team2Player1->name }} & {{ $match->team2Player2->name }}</td>

                    <form method="POST" action="{{ route('matches.doubles_mixed.update', ['id' => $match->id]) }}">
                        @csrf
                        @method('PUT')

                        <td>
                            <select name="stage" class="form-control" required>
                                @foreach($stages as $stage)
                                    <option value="{{ $stage }}" {{ $match->stage == $stage ? 'selected' : '' }}>
                                        {{ $stage }}
                                    </option>
                                @endforeach
                            </select>
                        </td>

                        <td>
                            <input type="date" name="match_date" value="{{ $match->match_date }}" class="form-control" required>
                        </td>
                        <td>
                            <input type="time" name="match_time" value="{{ \Carbon\Carbon::parse($match->match_time)->format('H:i') }}" class="form-control" required>
                        </td>
                        <td>
                            <input type="number" name="set1_team1_points" value="{{ $match->set1_team1_points ?? 0 }}" class="form-control" required>
                            <input type="number" name="set1_team2_points" value="{{ $match->set1_team2_points ?? 0 }}" class="form-control" required>
                        </td>
                        <td>
                            <input type="number" name="set2_team1_points" value="{{ $match->set2_team1_points ?? 0 }}" class="form-control" required>
                            <input type="number" name="set2_team2_points" value="{{ $match->set2_team2_points ?? 0 }}" class="form-control" required>
                        </td>
                        <td>
                            <input type="number" name="set3_team1_points" value="{{ $match->set3_team1_points ?? 0 }}" class="form-control">
                            <input type="number" name="set3_team2_points" value="{{ $match->set3_team2_points ?? 0 }}" class="form-control">
                        </td>

                        <td>
                            <button type="submit" class="btn btn-primary btn-sm">Update</button>
                        </form>

                        <form method="POST" action="{{ route('matches.doubles_mixed.destroy', ['id' => $match->id]) }}" 
                              onsubmit="return confirm('Are you sure you want to delete this match?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                        </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @else
        <p>No matches found.</p>
    @endif
</div>
@endsection
