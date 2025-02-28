doubles index
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Doubles Matches (BD, GD, XD)</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Filters Row -->
    <form method="GET" action="{{ route('matches.doubles.index') }}" class="mb-3">
        <div class="d-flex flex-wrap align-items-center gap-2">
            <!-- Tournament Filter -->
            <label for="filter_tournament">Tournament:</label>
            <select name="filter_tournament" id="filter_tournament" class="form-control w-auto">
                <option value="all" {{ request('filter_tournament', 'all') == 'all' ? 'selected' : '' }}>All</option>
                @foreach($tournaments as $tournament)
                    <option value="{{ $tournament->id }}" {{ request('filter_tournament') == $tournament->id ? 'selected' : '' }}>
                        {{ $tournament->name }}
                    </option>
                @endforeach
            </select>

            <!-- Player Filter -->
            <label for="filter_player">Player:</label>
            <select name="filter_player" id="filter_player" class="form-control w-auto">
                <option value="all" {{ request('filter_player', 'all') == 'all' ? 'selected' : '' }}>All</option>
                @foreach($players as $player)
                    <option value="{{ $player->id }}" {{ request('filter_player') == $player->id ? 'selected' : '' }}>
                        {{ $player->name }}
                    </option>
                @endforeach
            </select>

            <!-- Category Filter -->
            <label for="filter_category">Category:</label>
            <select name="filter_category" id="filter_category" class="form-control w-auto">
                <option value="all" {{ request('filter_category', 'all') == 'all' ? 'selected' : '' }}>All</option>
                <option value="BD" {{ request('filter_category') == 'BD' ? 'selected' : '' }}>Boys Doubles (BD)</option>
                <option value="GD" {{ request('filter_category') == 'GD' ? 'selected' : '' }}>Girls Doubles (GD)</option>
                <option value="XD" {{ request('filter_category') == 'XD' ? 'selected' : '' }}>Mixed Doubles (XD)</option>
            </select>

            <!-- Date Filter -->
            <label for="filter_date">Date:</label>
            <input type="date" name="filter_date" id="filter_date" class="form-control w-auto" value="{{ request('filter_date') }}">

            <!-- Stage Filter -->
            <label for="filter_stage">Stage:</label>
            <select name="filter_stage" id="filter_stage" class="form-control w-auto">
                <option value="all" {{ request('filter_stage', 'all') == 'all' ? 'selected' : '' }}>All</option>
                <option value="Pre Quarter Finals" {{ request('filter_stage') == 'Pre Quarter Finals' ? 'selected' : '' }}>Pre Quarter Finals</option>
                <option value="Quarter Finals" {{ request('filter_stage') == 'Quarter Finals' ? 'selected' : '' }}>Quarter Finals</option>
                <option value="Semifinals" {{ request('filter_stage') == 'Semifinals' ? 'selected' : '' }}>Semifinals</option>
                <option value="Finals" {{ request('filter_stage') == 'Finals' ? 'selected' : '' }}>Finals</option>
            </select>

            <!-- Results Filter -->
            <label for="filter_results">Results:</label>
            <select name="filter_results" id="filter_results" class="form-control w-auto">
                <option value="all" {{ request('filter_results', 'all') == 'all' ? 'selected' : '' }}>All</option>
                <option value="Team 1" {{ request('filter_results') == 'Team 1' ? 'selected' : '' }}>Team 1 Won</option>
                <option value="Team 2" {{ request('filter_results') == 'Team 2' ? 'selected' : '' }}>Team 2 Won</option>
                <option value="Draw" {{ request('filter_results') == 'Draw' ? 'selected' : '' }}>Draw</option>
            </select>

            <button type="submit" class="btn btn-primary">Apply Filters</button>
        </div>
    </form>

    <!-- Matches Table -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Match ID</th>
                <th>Tournament</th>
                <th>Category</th>
                <th>Team 1</th>
                <th>Team 2</th>
                <th>Stage</th>
                <th>Match Date</th>
                <th>Match Time</th>
                <th>Set 1 (T1 - T2)</th>
                <th>Set 2 (T1 - T2)</th>
                <th>Set 3 (T1 - T2)</th>
                <th>Winner</th>
            </tr>
        </thead>
        <tbody>
            @foreach($matches as $match)
            <tr>
                <td>{{ $match->id }}</td>
                <td>{{ $match->tournament->name ?? 'N/A' }}</td>
                <td>{{ $match->category->name ?? 'N/A' }}</td>
                <td>{{ $match->team1Player1->name ?? 'N/A' }} & {{ $match->team1Player2->name ?? 'N/A' }}</td>
                <td>{{ $match->team2Player1->name ?? 'N/A' }} & {{ $match->team2Player2->name ?? 'N/A' }}</td>
                <td>{{ $match->stage }}</td>
                <td>{{ $match->match_date }}</td>
                <td>{{ $match->match_time }}</td>
                <td>{{ $match->set1_team1_points }} - {{ $match->set1_team2_points }}</td>
                <td>{{ $match->set2_team1_points }} - {{ $match->set2_team2_points }}</td>
                <td>
                    {{ $match->set3_team1_points !== null ? $match->set3_team1_points : 'N/A' }} - 
                    {{ $match->set3_team2_points !== null ? $match->set3_team2_points : 'N/A' }}
                </td>
                <td>
                    @php
                        $team1_sets = ($match->set1_team1_points > $match->set1_team2_points) + ($match->set2_team1_points > $match->set2_team2_points) + ($match->set3_team1_points > $match->set3_team2_points);
                        $team2_sets = ($match->set1_team2_points > $match->set1_team1_points) + ($match->set2_team2_points > $match->set2_team1_points) + ($match->set3_team2_points > $match->set3_team1_points);
                    @endphp
                    {{ $team1_sets > $team2_sets ? 'Team 1' : ($team2_sets > $team1_sets ? 'Team 2' : 'Draw') }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $matches->appends(request()->query())->links() }}
    </div>

</div>
@endsection
