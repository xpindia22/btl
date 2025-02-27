@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">Doubles Matches (Read Only)</h1>

    @if(session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <!-- We'll have 2 rows for sets, so let's merge some headers via rowSpan -->
                    <th rowspan="2">ID</th>
                    <th rowspan="2">Tournament</th>
                    <th rowspan="2">Category</th>
                    
                    <!-- This will label each team row -->
                    <th>Team</th>
                    
                    <!-- We'll have Set1, Set2, Set3 columns for each row (Team1 & Team2) -->
                    <th colspan="3">Set Scores</th>
                    
                    <th rowspan="2">Date</th>
                    <th rowspan="2">Time</th>
                    <th rowspan="2">Winner</th>
                </tr>
                <tr>
                    <!-- Second row of headers: "Team" is already in the row above 
                         Now we specify which sets each row will show -->
                    <th>Team</th>
                    <th>Set 1</th>
                    <th>Set 2</th>
                    <th>Set 3</th>
                </tr>
            </thead>

            <tbody>
            @forelse($matches as $match)
                @php
                    // Auto-calculate winner (2 out of 3 sets)
                    $team1SetsWon = 0;
                    $team2SetsWon = 0;

                    // Set 1
                    if(($match->set1_team1_points ?? 0) > ($match->set1_team2_points ?? 0)) {
                        $team1SetsWon++;
                    } elseif(($match->set1_team1_points ?? 0) < ($match->set1_team2_points ?? 0)) {
                        $team2SetsWon++;
                    }

                    // Set 2
                    if(($match->set2_team1_points ?? 0) > ($match->set2_team2_points ?? 0)) {
                        $team1SetsWon++;
                    } elseif(($match->set2_team1_points ?? 0) < ($match->set2_team2_points ?? 0)) {
                        $team2SetsWon++;
                    }

                    // Set 3 (only if both are not null)
                    if(!is_null($match->set3_team1_points) && !is_null($match->set3_team2_points)) {
                        if($match->set3_team1_points > $match->set3_team2_points) {
                            $team1SetsWon++;
                        } elseif($match->set3_team1_points < $match->set3_team2_points) {
                            $team2SetsWon++;
                        }
                    }

                    // Determine winner
                    $winner = 'TBD';
                    if($team1SetsWon > $team2SetsWon) {
                        $winner = optional($match->team1Player1)->name . ' & ' .
                                  optional($match->team1Player2)->name;
                    } elseif($team2SetsWon > $team1SetsWon) {
                        $winner = optional($match->team2Player1)->name . ' & ' .
                                  optional($match->team2Player2)->name;
                    }
                @endphp

                <!-- FIRST ROW: Team 1 -->
                <tr>
                    <!-- We need rowSpan=2 so we can display T1 & T2 side by side in 2 rows. -->
                    <td rowspan="2">{{ $match->id }}</td>
                    <td rowspan="2">{{ optional($match->tournament)->name ?? 'N/A' }}</td>
                    <td rowspan="2">{{ optional($match->category)->name ?? 'N/A' }}</td>

                    <!-- Team 1 name(s) -->
                    <td>
                        {{ optional($match->team1Player1)->name ?? 'N/A' }} &
                        {{ optional($match->team1Player2)->name ?? 'N/A' }}
                    </td>

                    <!-- Set 1/2/3 for Team 1 -->
                    <td>{{ $match->set1_team1_points ?? 0 }}</td>
                    <td>{{ $match->set2_team1_points ?? 0 }}</td>
                    <td>{{ $match->set3_team1_points ?? 0 }}</td>

                    <!-- rowSpan for date/time/winner so they appear in middle for both teams -->
                    <td rowspan="2">{{ $match->match_date ?? 'N/A' }}</td>
                    <td rowspan="2">
                        <!-- MATCH TIME from the DB column -->
                        {{ $match->match_time ?? 'N/A' }}
                    </td>
                    <td rowspan="2">{{ $winner }}</td>
                </tr>

                <!-- SECOND ROW: Team 2 -->
                <tr>
                    <td>
                        {{ optional($match->team2Player1)->name ?? 'N/A' }} &
                        {{ optional($match->team2Player2)->name ?? 'N/A' }}
                    </td>
                    <td>{{ $match->set1_team2_points ?? 0 }}</td>
                    <td>{{ $match->set2_team2_points ?? 0 }}</td>
                    <td>{{ $match->set3_team2_points ?? 0 }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10">No matches found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
