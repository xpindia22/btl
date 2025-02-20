@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Doubles Matches (BD, GD, XD)</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Filter Dropdown for BD, GD, XD Matches -->
    <form method="GET" action="{{ route('matches.doubles.edit') }}" class="mb-3">
        <label for="filter_category">Filter by Category:</label>
        <select name="filter_category" id="filter_category" class="form-control w-25 d-inline">
            <option value="all" {{ $filterCategory == 'all' ? 'selected' : '' }}>All Matches</option>
            <option value="BD" {{ $filterCategory == 'BD' ? 'selected' : '' }}>Boys Doubles (BD)</option>
            <option value="GD" {{ $filterCategory == 'GD' ? 'selected' : '' }}>Girls Doubles (GD)</option>
            <option value="XD" {{ $filterCategory == 'XD' ? 'selected' : '' }}>Mixed Doubles (XD)</option>
        </select>
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>

    <form method="POST" action="{{ route('matches.doubles.updateMultiple') }}">
        @csrf
        @method('PUT')

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
                    <td>
                        {{ $match->team1Player1->name ?? 'N/A' }} & 
                        {{ $match->team1Player2->name ?? 'N/A' }}
                    </td>
                    <td>
                        {{ $match->team2Player1->name ?? 'N/A' }} & 
                        {{ $match->team2Player2->name ?? 'N/A' }}
                    </td>
                    <td>
                        <select name="matches[{{ $match->id }}][stage]" class="form-control">
                            <option value="Pre Quarter Finals" {{ $match->stage == 'Pre Quarter Finals' ? 'selected' : '' }}>Pre Quarter Finals</option>
                            <option value="Quarter Finals" {{ $match->stage == 'Quarter Finals' ? 'selected' : '' }}>Quarter Finals</option>
                            <option value="Semifinals" {{ $match->stage == 'Semifinals' ? 'selected' : '' }}>Semifinals</option>
                            <option value="Finals" {{ $match->stage == 'Finals' ? 'selected' : '' }}>Finals</option>
                        </select>
                    </td>
                    <td><input type="date" name="matches[{{ $match->id }}][match_date]" class="form-control" value="{{ $match->match_date }}"></td>
                    <td><input type="time" name="matches[{{ $match->id }}][match_time]" class="form-control" value="{{ $match->match_time }}"></td>
                    <td>
    <input type="number" name="matches[{{ $match->id }}][set1_team1_points]" class="form-control" 
           value="{{ old('matches.'.$match->id.'.set1_team1_points', $match->set1_team1_points ?? 0) }}">
    -
    <input type="number" name="matches[{{ $match->id }}][set1_team2_points]" class="form-control" 
           value="{{ old('matches.'.$match->id.'.set1_team2_points', $match->set1_team2_points ?? 0) }}">
</td>
<td>
    <input type="number" name="matches[{{ $match->id }}][set2_team1_points]" class="form-control" 
           value="{{ old('matches.'.$match->id.'.set2_team1_points', $match->set2_team1_points ?? 0) }}">
    -
    <input type="number" name="matches[{{ $match->id }}][set2_team2_points]" class="form-control" 
           value="{{ old('matches.'.$match->id.'.set2_team2_points', $match->set2_team2_points ?? 0) }}">
</td>
<td>
    <input type="number" name="matches[{{ $match->id }}][set3_team1_points]" class="form-control" 
           value="{{ old('matches.'.$match->id.'.set3_team1_points', $match->set3_team1_points ?? 0) }}">
    -
    <input type="number" name="matches[{{ $match->id }}][set3_team2_points]" class="form-control" 
           value="{{ old('matches.'.$match->id.'.set3_team2_points', $match->set3_team2_points ?? 0) }}">
</td>



                    <td>
                        @php
                            // Count sets won by each team
                            $team1_sets = 0;
                            $team2_sets = 0;

                            if ($match->set1_team1_points > $match->set1_team2_points) {
                                $team1_sets++;
                            } elseif ($match->set1_team2_points > $match->set1_team1_points) {
                                $team2_sets++;
                            }

                            if ($match->set2_team1_points > $match->set2_team2_points) {
                                $team1_sets++;
                            } elseif ($match->set2_team2_points > $match->set2_team1_points) {
                                $team2_sets++;
                            }

                            if (!is_null($match->set3_team1_points) && !is_null($match->set3_team2_points)) {
                                if ($match->set3_team1_points > $match->set3_team2_points) {
                                    $team1_sets++;
                                } elseif ($match->set3_team2_points > $match->set3_team1_points) {
                                    $team2_sets++;
                                }
                            }

                            // Determine winner
                            if ($team1_sets > $team2_sets) {
                                echo "Team 1";
                            } elseif ($team2_sets > $team1_sets) {
                                echo "Team 2";
                            } else {
                                echo "Draw";
                            }
                        @endphp
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination Links -->
        <div class="d-flex justify-content-center">
            {{ $matches->withQueryString()->links() }}
        </div>

        <button type="submit" class="btn btn-success mt-3">Update Matches</button>
    </form>
</div>
@endsection
