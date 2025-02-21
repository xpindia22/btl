@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Singles Matches (View-Only)</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Filters Row -->
    <form method="GET" action="{{ route('matches.singles.index') }}" class="mb-3">
        <div class="d-flex flex-wrap align-items-center gap-2">
            <!-- Tournament Filter -->
            <label for="filter_tournament">Tournament:</label>
            <select name="filter_tournament" id="filter_tournament" class="form-control w-auto" onchange="this.form.submit()">
                <option value="all" {{ request('filter_tournament', 'all') == 'all' ? 'selected' : '' }}>All</option>
                @foreach($tournaments as $tournament)
                    <option value="{{ $tournament->id }}" {{ request('filter_tournament') == $tournament->id ? 'selected' : '' }}>
                        {{ $tournament->name }}
                    </option>
                @endforeach
            </select>

            <!-- Player Filter -->
            <label for="filter_player">Player:</label>
            <select name="filter_player" id="filter_player" class="form-control w-auto" onchange="this.form.submit()">
                <option value="all" {{ request('filter_player', 'all') == 'all' ? 'selected' : '' }}>All</option>
                @foreach($players as $player)
                    <option value="{{ $player->id }}" {{ request('filter_player') == $player->id ? 'selected' : '' }}>
                        {{ $player->name }}
                    </option>
                @endforeach
            </select>

            <!-- Category Filter -->
            <label for="filter_category">Category:</label>
            <select name="filter_category" id="filter_category" class="form-control w-auto" onchange="this.form.submit()">
                <option value="all" {{ request('filter_category', 'all') == 'all' ? 'selected' : '' }}>All</option>
                <option value="BS" {{ request('filter_category') == 'BS' ? 'selected' : '' }}>Boys Singles (BS)</option>
                <option value="GS" {{ request('filter_category') == 'GS' ? 'selected' : '' }}>Girls Singles (GS)</option>
            </select>

            <!-- Date Filter -->
            <label for="filter_date">Date:</label>
            <input type="date" name="filter_date" id="filter_date" class="form-control w-auto" value="{{ request('filter_date') }}" onchange="this.form.submit()">

            <!-- Stage Filter -->
            <label for="filter_stage">Stage:</label>
            <select name="filter_stage" id="filter_stage" class="form-control w-auto" onchange="this.form.submit()">
                <option value="all" {{ request('filter_stage', 'all') == 'all' ? 'selected' : '' }}>All</option>
                <option value="Pre Quarter Finals" {{ request('filter_stage') == 'Pre Quarter Finals' ? 'selected' : '' }}>Pre Quarter Finals</option>
                <option value="Quarter Finals" {{ request('filter_stage') == 'Quarter Finals' ? 'selected' : '' }}>Quarter Finals</option>
                <option value="Semifinals" {{ request('filter_stage') == 'Semifinals' ? 'selected' : '' }}>Semifinals</option>
                <option value="Finals" {{ request('filter_stage') == 'Finals' ? 'selected' : '' }}>Finals</option>
            </select>

            <!-- Results Filter -->
            <label for="filter_results">Results:</label>
            <select name="filter_results" id="filter_results" class="form-control w-auto" onchange="this.form.submit()">
                <option value="all" {{ request('filter_results', 'all') == 'all' ? 'selected' : '' }}>All</option>
                <option value="Player 1" {{ request('filter_results') == 'Player 1' ? 'selected' : '' }}>Player 1 Won</option>
                <option value="Player 2" {{ request('filter_results') == 'Player 2' ? 'selected' : '' }}>Player 2 Won</option>
                <option value="Draw" {{ request('filter_results') == 'Draw' ? 'selected' : '' }}>Draw</option>
            </select>
        </div>
    </form>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Tournament</th>
                <th>Category</th>
                <th>Player 1</th>
                <th>Player 2</th>
                <th>Stage</th>
                <th>Date</th>
                <th>Time</th>
                <th>Set 1</th>
                <th>Set 2</th>
                <th>Set 3</th>
                <th>Winner</th>
            </tr>
        </thead>
        <tbody>
            @foreach($matches as $key => $match)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ optional($match->tournament)->name ?? 'N/A' }}</td>
                    <td>{{ optional($match->category)->name ?? 'N/A' }}</td>
                    <td>{{ optional($match->player1)->name ?? 'N/A' }}</td>
                    <td>{{ optional($match->player2)->name ?? 'N/A' }}</td>
                    <td>{{ $match->stage ?? 'N/A' }}</td>
                    <td>{{ $match->match_date ?? 'N/A' }}</td>
                    <td>{{ $match->match_time ?? 'N/A' }}</td>
                    <td>{{ $match->set1_player1_points ?? 0 }} - {{ $match->set1_player2_points ?? 0 }}</td>
                    <td>{{ $match->set2_player1_points ?? 0 }} - {{ $match->set2_player2_points ?? 0 }}</td>
                    <td>
                        @if(!is_null($match->set3_player1_points) && !is_null($match->set3_player2_points))
                            {{ $match->set3_player1_points }} - {{ $match->set3_player2_points }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        @php
                            $p1_sets = 0; 
                            $p2_sets = 0;

                            if ($match->set1_player1_points > $match->set1_player2_points) $p1_sets++;
                            if ($match->set1_player2_points > $match->set1_player1_points) $p2_sets++;

                            if ($match->set2_player1_points > $match->set2_player2_points) $p1_sets++;
                            if ($match->set2_player2_points > $match->set2_player1_points) $p2_sets++;

                            if (!is_null($match->set3_player1_points) && !is_null($match->set3_player2_points)) {
                                if ($match->set3_player1_points > $match->set3_player2_points) $p1_sets++;
                                if ($match->set3_player2_points > $match->set3_player1_points) $p2_sets++;
                            }

                            $winner = $p1_sets > $p2_sets ? optional($match->player1)->name : 
                                      ($p2_sets > $p1_sets ? optional($match->player2)->name : 'Draw');
                        @endphp
                        {{ $winner }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center">
        {{ $matches->appends(request()->query())->links() }}
    </div>
</div>
@endsection
