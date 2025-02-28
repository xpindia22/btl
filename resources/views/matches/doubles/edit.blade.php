@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Doubles Matches - Edit (Inline)</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- (Optional: Place your filter form here) -->

    <table class="table table-bordered table-responsive">
        <thead>
            <tr>
                <th rowspan="2">Match ID</th>
                <th rowspan="2">Tournament</th>
                <th rowspan="2">Category</th>
                <th colspan="2">Teams</th>
                <th rowspan="2">Stage</th>
                <th rowspan="2">Match Date</th>
                <th rowspan="2">Match Time</th>
                <th colspan="3">Sets</th>
                <th rowspan="2">Action</th>
            </tr>
            <tr>
                <th>Team 1</th>
                <th>Team 2</th>
                <th>Set 1</th>
                <th>Set 2</th>
                <th>Set 3</th>
            </tr>
        </thead>
        <tbody>
            @foreach($matches as $match)
                <!-- Update form wrapping two rows for the match -->
                <form action="{{ route('matches.doubles.update', $match->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <tr>
                        <!-- Common columns with rowspan="2" -->
                        <td rowspan="2">{{ $match->id }}</td>
                        <td rowspan="2">{{ $match->tournament->name ?? 'N/A' }}</td>
                        <td rowspan="2">{{ $match->category->name ?? 'N/A' }}</td>
                        
                        <!-- First row: Team 1 -->
                        <td>
                            <!-- Display Team 1 players on separate lines -->
                            {{ $match->team1Player1->name ?? 'N/A' }}<br>
                            {{ $match->team1Player2->name ?? 'N/A' }}
                        </td>
                        <td rowspan="2">
                            <!-- Second row will display Team 2 players -->
                        </td>
                        <td rowspan="2">
                            <input type="text" name="stage" class="form-control" value="{{ $match->stage }}">
                        </td>
                        <td rowspan="2">
                            <input type="date" name="match_date" class="form-control" value="{{ $match->match_date }}">
                        </td>
                        <td rowspan="2">
                            <input type="time" name="match_time" class="form-control" value="{{ $match->match_time }}">
                        </td>
                        <!-- Set scores for Team 1 -->
                        <td>
                            <input type="number" name="set1_team1_points" class="form-control" 
                                   value="{{ $match->set1_team1_points }}" style="width:60px;">
                        </td>
                        <td>
                            <input type="number" name="set2_team1_points" class="form-control" 
                                   value="{{ $match->set2_team1_points }}" style="width:60px;">
                        </td>
                        <td>
                            <input type="number" name="set3_team1_points" class="form-control" 
                                   value="{{ $match->set3_team1_points }}" style="width:60px;">
                        </td>
                        <td rowspan="2">
                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                        </td>
                    </tr>
                    <tr>
                        <!-- Second row: Team 2 -->
                        <td>
                            <!-- Display Team 2 players on separate lines -->
                            {{ $match->team2Player1->name ?? 'N/A' }}<br>
                            {{ $match->team2Player2->name ?? 'N/A' }}
                        </td>
                        <td>
                            <input type="number" name="set1_team2_points" class="form-control" 
                                   value="{{ $match->set1_team2_points }}" style="width:60px;">
                        </td>
                        <td>
                            <input type="number" name="set2_team2_points" class="form-control" 
                                   value="{{ $match->set2_team2_points }}" style="width:60px;">
                        </td>
                        <td>
                            <input type="number" name="set3_team2_points" class="form-control" 
                                   value="{{ $match->set3_team2_points }}" style="width:60px;">
                        </td>
                    </tr>
                </form>
                <!-- Delete form in a separate row to avoid nesting -->
                <tr>
                    <td colspan="12" class="text-right">
                        <form action="{{ route('matches.doubles.delete', $match->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this match?')">
                                Delete Match #{{ $match->id }}
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $matches->appends(request()->query())->links('vendor.pagination.default') }}
    </div>
</div>
@endsection
