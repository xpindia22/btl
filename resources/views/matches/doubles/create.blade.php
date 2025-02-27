@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">Doubles Matches (Read Only)</h1>

    @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    <!-- Filters (Optional) -->
    <form method="GET" action="{{ route('matches.doubles.index') }}" class="d-flex flex-wrap align-items-center gap-2 mb-3">

{{-- Tournament --}}
<label for="filter_tournament" class="mb-0 mr-2">Tournament:</label>
<select name="filter_tournament" id="filter_tournament" class="form-control w-auto">
    <option value="all" {{ request('filter_tournament','all')=='all' ? 'selected' : '' }}>All</option>
    @foreach($tournaments as $t)
        <option value="{{ $t->id }}" {{ request('filter_tournament') == $t->id ? 'selected' : '' }}>
            {{ $t->name }}
        </option>
    @endforeach
</select>

{{-- Category --}}
<label for="filter_category" class="mb-0 mr-2">Category:</label>
<select name="filter_category" id="filter_category" class="form-control w-auto">
    <option value="all" {{ request('filter_category','all')=='all' ? 'selected' : '' }}>All</option>
    <option value="BD" {{ request('filter_category')=='BD' ? 'selected' : '' }}>BD</option>
    <option value="GD" {{ request('filter_category')=='GD' ? 'selected' : '' }}>GD</option>
    <option value="XD" {{ request('filter_category')=='XD' ? 'selected' : '' }}>XD</option>
</select>

{{-- Team 1 --}}
<label for="filter_team1" class="mb-0 mr-2">Team 1:</label>
<input type="text" name="filter_team1" id="filter_team1" class="form-control w-auto"
       value="{{ request('filter_team1') }}" placeholder="Team 1 name">

{{-- Team 2 --}}
<label for="filter_team2" class="mb-0 mr-2">Team 2:</label>
<input type="text" name="filter_team2" id="filter_team2" class="form-control w-auto"
       value="{{ request('filter_team2') }}" placeholder="Team 2 name">

{{-- Stage --}}
<label for="filter_stage" class="mb-0 mr-2">Stage:</label>
<select name="filter_stage" id="filter_stage" class="form-control w-auto">
    <option value="all" {{ request('filter_stage','all')=='all' ? 'selected' : '' }}>All</option>
    @foreach(['Pre Quarter Finals','Quarter Finals','Semifinals','Finals','Preliminary'] as $stage)
        <option value="{{ $stage }}" {{ request('filter_stage') == $stage ? 'selected' : '' }}>
            {{ $stage }}
        </option>
    @endforeach
</select>

{{-- Match Date --}}
<label for="filter_match_date" class="mb-0 mr-2">Match Date:</label>
<input type="date" name="filter_match_date" id="filter_match_date"
       class="form-control w-auto"
       value="{{ request('filter_match_date') }}">

{{-- Winner --}}
<label for="filter_winner" class="mb-0 mr-2">Winner:</label>
<input type="text" name="filter_winner" id="filter_winner" class="form-control w-auto"
       value="{{ request('filter_winner') }}" placeholder="Winner name">

<button type="submit" class="btn btn-primary ml-2">Search</button>
</form>


    <!-- Read-Only Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Tournament</th>
                    <th>Category</th>
                    <th>Team 1</th>
                    <th>Team 2</th>
                    <th>Stage</th>
                    <th>Set 1 (T1 - T2)</th>
                    <th>Set 2 (T1 - T2)</th>
                    <th>Set 3 (T1 - T2)</th>
                    <th>Match Date</th>
                    <th>Match Time</th>
                    <th>Winner</th>
                </tr>
            </thead>
            <tbody>
                @foreach($matches as $match)
                    @php
                        // Calculate sets won by each team
                        $team1SetsWon = 0;
                        $team2SetsWon = 0;

                        // Set 1
                        if (($match->set1_team1_points ?? 0) > ($match->set1_team2_points ?? 0)) {
                            $team1SetsWon++;
                        } elseif (($match->set1_team1_points ?? 0) < ($match->set1_team2_points ?? 0)) {
                            $team2SetsWon++;
                        }

                        // Set 2
                        if (($match->set2_team1_points ?? 0) > ($match->set2_team2_points ?? 0)) {
                            $team1SetsWon++;
                        } elseif (($match->set2_team1_points ?? 0) < ($match->set2_team2_points ?? 0)) {
                            $team2SetsWon++;
                        }

                        // Set 3 (only if both sets exist)
                        if (!is_null($match->set3_team1_points) && !is_null($match->set3_team2_points)) {
                            if (($match->set3_team1_points ?? 0) > ($match->set3_team2_points ?? 0)) {
                                $team1SetsWon++;
                            } elseif (($match->set3_team1_points ?? 0) < ($match->set3_team2_points ?? 0)) {
                                $team2SetsWon++;
                            }
                        }

                        // Determine winner
                        $winner = 'TBD';
                        if ($team1SetsWon > $team2SetsWon) {
                            $winner = optional($match->team1Player1)->name . ' & ' . optional($match->team1Player2)->name;
                        } elseif ($team2SetsWon > $team1SetsWon) {
                            $winner = optional($match->team2Player1)->name . ' & ' . optional($match->team2Player2)->name;
                        }
                    @endphp

                    <tr>
                        <td>{{ $match->id }}</td>
                        <td>{{ optional($match->tournament)->name ?? 'N/A' }}</td>
                        <td>{{ optional($match->category)->name ?? 'N/A' }}</td>
                        <td>
                            {{ optional($match->team1Player1)->name ?? 'N/A' }} &
                            {{ optional($match->team1Player2)->name ?? 'N/A' }}
                        </td>
                        <td>
                            {{ optional($match->team2Player1)->name ?? 'N/A' }} &
                            {{ optional($match->team2Player2)->name ?? 'N/A' }}
                        </td>
                        <td>{{ $match->stage ?? 'N/A' }}</td>
                        
                        <!-- Set 1, 2, 3 -->
                        <td>{{ $match->set1_team1_points ?? 0 }} - {{ $match->set1_team2_points ?? 0 }}</td>
                        <td>{{ $match->set2_team1_points ?? 0 }} - {{ $match->set2_team2_points ?? 0 }}</td>
                        <td>{{ $match->set3_team1_points ?? 0 }} - {{ $match->set3_team2_points ?? 0 }}</td>

                        <!-- Date & Time -->
                        <td>{{ $match->match_date ?? 'N/A' }}</td>
                        <td>{{ $match->match_time ?? 'N/A' }}</td>

                        <!-- Auto-calculated winner -->
                        <td>{{ $winner }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $matches->appends(request()->query())->links() }}
    </div>
</div>
@endsection
