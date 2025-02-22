@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">Doubles Matches (BD, GD, XD)</h1>

    @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    <!-- Filters Row in One Line -->
    <form method="GET" action="{{ route('matches.doubles.index') }}" class="mb-3">
        <div class="d-flex align-items-center flex-nowrap" style="overflow-x: auto;">
            <div class="form-group mr-3">
                <label for="filter_tournament" class="mr-1">Tournament:</label>
                <select name="filter_tournament" id="filter_tournament" class="form-control" style="width: auto; display: inline-block;">
                    <option value="all" {{ request('filter_tournament', 'all') == 'all' ? 'selected' : '' }}>All</option>
                    @foreach($tournaments as $tournament)
                        <option value="{{ $tournament->id }}" {{ request('filter_tournament') == $tournament->id ? 'selected' : '' }}>
                            {{ $tournament->name }}
                        </option>
                    @endforeach
                </select>
            
                <label for="filter_player" class="mr-1">Player:</label>
                <select name="filter_player" id="filter_player" class="form-control" style="width: auto; display: inline-block;">
                    <option value="all" {{ request('filter_player', 'all') == 'all' ? 'selected' : '' }}>All</option>
                    @foreach($players as $player)
                        <option value="{{ $player->id }}" {{ request('filter_player') == $player->id ? 'selected' : '' }}>
                            {{ $player->name }}
                        </option>
                    @endforeach
                </select>
            
                <label for="filter_category" class="mr-1">Category:</label>
                <select name="filter_category" id="filter_category" class="form-control" style="width: auto; display: inline-block;">
                    <option value="all" {{ request('filter_category', 'all') == 'all' ? 'selected' : '' }}>All</option>
                    <option value="BD" {{ request('filter_category') == 'BD' ? 'selected' : '' }}>Boys Doubles (BD)</option>
                    <option value="GD" {{ request('filter_category') == 'GD' ? 'selected' : '' }}>Girls Doubles (GD)</option>
                    <option value="XD" {{ request('filter_category') == 'XD' ? 'selected' : '' }}>Mixed Doubles (XD)</option>
                </select>
                <label for="filter_date" class="mr-1">Date:</label>
                <input type="date" name="filter_date" id="filter_date" class="form-control" style="width: auto; display: inline-block;" placeholder="dd-mm-yyyy" value="{{ request('filter_date') }}">
            
                <label for="filter_stage" class="mr-1">Stage:</label>
                <select name="filter_stage" id="filter_stage" class="form-control" style="width: auto; display: inline-block;">
                    <option value="all" {{ request('filter_stage', 'all') == 'all' ? 'selected' : '' }}>All</option>
                    <option value="Pre Quarter Finals" {{ request('filter_stage') == 'Pre Quarter Finals' ? 'selected' : '' }}>Pre Quarter Finals</option>
                    <option value="Quarter Finals" {{ request('filter_stage') == 'Quarter Finals' ? 'selected' : '' }}>Quarter Finals</option>
                    <option value="Semifinals" {{ request('filter_stage') == 'Semifinals' ? 'selected' : '' }}>Semifinals</option>
                    <option value="Finals" {{ request('filter_stage') == 'Finals' ? 'selected' : '' }}>Finals</option>
                </select>
            
                <label for="filter_results" class="mr-1">Results:</label>
                <select name="filter_results" id="filter_results" class="form-control" style="width: auto; display: inline-block;">
                    <option value="all" {{ request('filter_results', 'all') == 'all' ? 'selected' : '' }}>All</option>
                    <option value="Team 1" {{ request('filter_results') == 'Team 1' ? 'selected' : '' }}>Team 1 Won</option>
                    <option value="Team 2" {{ request('filter_results') == 'Team 2' ? 'selected' : '' }}>Team 2 Won</option>
                    <option value="Draw" {{ request('filter_results') == 'Draw' ? 'selected' : '' }}>Draw</option>
                </select>
            
                <button type="submit" class="btn btn-primary">Apply Filter</button>
            </div>
        </div>
    </form>

    <!-- Matches Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped mx-auto">
            <thead class="table-dark">
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
                    @if(!$match->category || (stripos($match->category->name, 'BD') === false && stripos($match->category->name, 'GD') === false && stripos($match->category->name, 'XD') === false))
                        @continue
                    @endif
                    <tr>
                        <td>{{ $match->id }}</td>
                        <td>{{ $match->tournament->name ?? 'N/A' }}</td>
                        <td>{{ $match->category->name ?? 'N/A' }}</td>
                        <td>
                            {{ $match->team1Player1->name ?? 'N/A' }}<br>
                            {{ $match->team1Player2->name ?? 'N/A' }}
                        </td>
                        <td>
                            {{ $match->team2Player1->name ?? 'N/A' }}<br>
                            {{ $match->team2Player2->name ?? 'N/A' }}
                        </td>
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
                                $team1_sets = ($match->set1_team1_points > $match->set1_team2_points) 
                                    + ($match->set2_team1_points > $match->set2_team2_points) 
                                    + ($match->set3_team1_points > $match->set3_team2_points);
                                $team2_sets = ($match->set1_team2_points > $match->set1_team1_points) 
                                    + ($match->set2_team2_points > $match->set2_team1_points) 
                                    + ($match->set3_team2_points > $match->set3_team1_points);
                            @endphp
                            {{ $team1_sets > $team2_sets ? 'Team 1' : ($team2_sets > $team1_sets ? 'Team 2' : 'Draw') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center">
    {{ $matches->appends(request()->query())->links('vendor.pagination.default') }}
</div>
 

<style>
    /* Responsive table wrapper */
    .table-responsive {
        overflow-x: auto;
    }
    /* Center the table */
    .table {
        margin: 0 auto;
    }
    /* Ensure table cells fit content and wrap player names appropriately */
    .table th, .table td {
        vertical-align: middle;
        text-align: center;
        white-space: nowrap;
    }
    /* Allow the player columns to break into multiple lines */
    .table td:nth-child(4),
    .table td:nth-child(5) {
        white-space: normal;
        word-wrap: break-word;
    }
    /* Adjust font size if needed */
    .table {
        font-size: 0.9rem;
    }
    /* Custom Pagination Styling */
    .pagination {
        margin: 0;
        padding: 0;
        list-style: none;
        display: flex;
        justify-content: center;
    }
    .pagination li {
        margin: 0 0.2rem;
    }
    .pagination .page-link {
        padding: 0.25rem 0.5rem !important;
        font-size: 0.85rem;
        line-height: 1;
    }
</style>
@endsection
