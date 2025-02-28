@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Doubles Matches - Edit (Inline)</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

<!-- Custom CSS for Filters -->
<style>
        /* Container for all filters */
        .filter-row {
            display: flex;
            flex-wrap: nowrap;
            align-items: center;
            gap: 0.5rem;
            overflow: hidden;
            width: 100%;
        }
        /* Each filter item */
        .filter-item {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            /* Allow items to shrink */
            flex: 1 1 0;
            min-width: 80px;
        }
        /* Adjust labels so they don't force wrapping */
        .filter-item label {
            white-space: nowrap;
            font-size: 0.9rem;
            margin-bottom: 0;
        }
        /* Adjust inputs and selects */
        .filter-item select,
        .filter-item input {
            flex: 1 1 auto;
            max-width: 120px;
            font-size: 0.9rem;
            padding: 0.25rem 0.5rem;
        }
        /* Ensure the apply button doesn't shrink too much */
        .filter-row button {
            flex-shrink: 0;
        }
    </style>

    <!-- Filters Form -->
    <form method="GET" action="{{ route('matches.doubles.index') }}" class="mb-3">
        <div class="filter-row">
            <!-- Tournament Filter -->
            <div class="filter-item">
                <label for="filter_tournament">Tournament:</label>
                <select name="filter_tournament" id="filter_tournament" class="form-control">
                    <option value="all" {{ request('filter_tournament', 'all') == 'all' ? 'selected' : '' }}>All</option>
                    @foreach($tournaments as $tournament)
                        <option value="{{ $tournament->id }}" {{ request('filter_tournament') == $tournament->id ? 'selected' : '' }}>
                            {{ $tournament->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Player Filter -->
            <div class="filter-item">
                <label for="filter_player">Player:</label>
                <select name="filter_player" id="filter_player" class="form-control">
                    <option value="all" {{ request('filter_player', 'all') == 'all' ? 'selected' : '' }}>All</option>
                    @foreach($players as $player)
                        <option value="{{ $player->id }}" {{ request('filter_player') == $player->id ? 'selected' : '' }}>
                            {{ $player->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Category Filter -->
            <div class="filter-item">
                <label for="filter_category">Category:</label>
                <select name="filter_category" id="filter_category" class="form-control">
                    <option value="all" {{ request('filter_category', 'all') == 'all' ? 'selected' : '' }}>All</option>
                    <option value="BD" {{ request('filter_category') == 'BD' ? 'selected' : '' }}>Boys Doubles (BD)</option>
                    <option value="GD" {{ request('filter_category') == 'GD' ? 'selected' : '' }}>Girls Doubles (GD)</option>
                    <option value="XD" {{ request('filter_category') == 'XD' ? 'selected' : '' }}>Mixed Doubles (XD)</option>
                </select>
            </div>

            <!-- Date Filter -->
            <div class="filter-item">
                <label for="filter_date">Date:</label>
                <input type="date" name="filter_date" id="filter_date" class="form-control"
                       value="{{ request('filter_date') }}">
            </div>

            <!-- Stage Filter -->
            <div class="filter-item">
                <label for="filter_stage">Stage:</label>
                <select name="filter_stage" id="filter_stage" class="form-control">
                    <option value="all" {{ request('filter_stage', 'all') == 'all' ? 'selected' : '' }}>All</option>
                    <option value="Pre Quarter Finals" {{ request('filter_stage') == 'Pre Quarter Finals' ? 'selected' : '' }}>Pre Quarter Finals</option>
                    <option value="Quarter Finals" {{ request('filter_stage') == 'Quarter Finals' ? 'selected' : '' }}>Quarter Finals</option>
                    <option value="Semifinals" {{ request('filter_stage') == 'Semifinals' ? 'selected' : '' }}>Semifinals</option>
                    <option value="Finals" {{ request('filter_stage') == 'Finals' ? 'selected' : '' }}>Finals</option>
                </select>
            </div>

            <!-- Results Filter -->
            <div class="filter-item">
                <label for="filter_results">Results:</label>
                <select name="filter_results" id="filter_results" class="form-control">
                    <option value="all" {{ request('filter_results', 'all') == 'all' ? 'selected' : '' }}>All</option>
                    <option value="Team 1" {{ request('filter_results') == 'Team 1' ? 'selected' : '' }}>Team 1 Won</option>
                    <option value="Team 2" {{ request('filter_results') == 'Team 2' ? 'selected' : '' }}>Team 2 Won</option>
                    <option value="Draw" {{ request('filter_results') == 'Draw' ? 'selected' : '' }}>Draw</option>
                </select>
            </div>

            <!-- Apply Filters Button -->
            <button type="submit" class="btn btn-primary">Apply Filters</button>
        </div>
    </form>

    <table class="table table-bordered">
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
                <th>Set 1 (T1 - T2)</th>
                <th>Set 2 (T1 - T2)</th>
                <th>Set 3 (T1 - T2)</th>
                <th>Actions</th>
                <th></th> <!-- For the delete form -->
            </tr>
        </thead>
        <tbody>
        @foreach($matches as $match)
            @php
                // Example "winner" calculation
                $team1_sets = ($match->set1_team1_points > $match->set1_team2_points)
                            + ($match->set2_team1_points > $match->set2_team2_points)
                            + (($match->set3_team1_points ?? 0) > ($match->set3_team2_points ?? 0));
                $team2_sets = ($match->set1_team2_points > $match->set1_team1_points)
                            + ($match->set2_team2_points > $match->set2_team1_points)
                            + (($match->set3_team2_points ?? 0) > ($match->set3_team1_points ?? 0));
                $winner = $team1_sets > $team2_sets ? 'Team 1' : ($team2_sets > $team1_sets ? 'Team 2' : 'Draw');
            @endphp
            <tr>
                <!-- Read-only columns -->
                <td>{{ $match->id }}</td>
                <td>{{ $match->tournament->name ?? 'N/A' }}</td>
                <td>{{ $match->category->name ?? 'N/A' }}</td>
                <td>{{ $match->team1Player1->name ?? 'N/A' }} & {{ $match->team1Player2->name ?? 'N/A' }}</td>
                <td>{{ $match->team2Player1->name ?? 'N/A' }} & {{ $match->team2Player2->name ?? 'N/A' }}</td>

                <!-- Update form: uses PUT to update this match -->
                <form action="{{ route('matches.doubles.update', $match->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <td>
                        <input type="text" name="stage" class="form-control" 
                               value="{{ $match->stage }}" style="width:120px">
                    </td>
                    <td>
                        <input type="date" name="match_date" class="form-control" 
                               value="{{ $match->match_date }}" style="width:140px">
                    </td>
                    <td>
                        <input type="time" name="match_time" class="form-control" 
                               value="{{ $match->match_time }}" style="width:120px">
                    </td>
                    <td>
                        <input type="number" name="set1_team1_points" value="{{ $match->set1_team1_points }}" style="width:60px">
                        -
                        <input type="number" name="set1_team2_points" value="{{ $match->set1_team2_points }}" style="width:60px">
                    </td>
                    <td>
                        <input type="number" name="set2_team1_points" value="{{ $match->set2_team1_points }}" style="width:60px">
                        -
                        <input type="number" name="set2_team2_points" value="{{ $match->set2_team2_points }}" style="width:60px">
                    </td>
                    <td>
                        <input type="number" name="set3_team1_points" value="{{ $match->set3_team1_points }}" style="width:60px">
                        -
                        <input type="number" name="set3_team2_points" value="{{ $match->set3_team2_points }}" style="width:60px">
                    </td>

                    <td>
                        <button type="submit" class="btn btn-sm btn-primary">Save</button>
                    </td>
                </form>

                <!-- Delete form: uses DELETE to remove this match -->
                <td>
                    <form action="{{ route('matches.doubles.delete', $match->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger"
                                onclick="return confirm('Delete this match?')">
                            Delete
                        </button>
                    </form>
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
