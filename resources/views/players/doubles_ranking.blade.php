@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Doubles Rankings (BD, GD, XD)</h2>

    <form method="GET" action="{{ route('players.doublesRanking') }}" class="mb-4">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Tournament</label>
                <select class="form-select" name="tournament_id" onchange="this.form.submit()">
                    <option value="">All Tournaments</option>
                    @foreach($tournaments as $tournament)
                        <option value="{{ $tournament->id }}" {{ request('tournament_id') == $tournament->id ? 'selected' : '' }}>
                            {{ $tournament->name }}
                        </option>
                    @endforeach
                </select>
 
                <label class="form-label">Category</label>
                <select class="form-select" name="category_id">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
  
                <label class="form-label">Player</label>
                <select class="form-select" name="player_id">
                    <option value="">All Players</option>
                    @foreach($playersList as $player)
                        <option value="{{ $player->id }}" {{ request('player_id') == $player->id ? 'selected' : '' }}>
                            {{ $player->name }} ({{ $player->uid }})
                        </option>
                    @endforeach
                </select>
 
                <label class="form-label">Date</label>
                <input type="date" class="form-control" name="date" value="{{ request('date') }}">
  
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </div>
    </form>

    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th># Ranking</th>
                <th>Team</th>
                <th>Category</th>
                <th>No. of Matches</th>
                <th>Total Points</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rankings as $index => $ranking)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $ranking->team_name }}</td>
                    <td>{{ $ranking->category_name }}</td>
                    <td>{{ $ranking->matches_played }}</td>
                    <td>{{ $ranking->total_points }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No ranking data available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
