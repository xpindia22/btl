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
                <th rowspan="2">Teams 1 &amp; 2</th>
                <th rowspan="2">Stage</th>
                <th rowspan="2">Match Date</th>
                <th rowspan="2">Match Time</th>
                <th colspan="3">Sets</th>
                <th rowspan="2">Save</th>
                <th rowspan="2">Delete</th>
            </tr>
            <tr>
                <th>Set 1</th>
                <th>Set 2</th>
                <th>Set 3</th>
            </tr>
        </thead>
        <tbody>
            @foreach($matches as $match)
                <tr>
                    <!-- Begin update form for this match -->
                    <form action="{{ route('matches.doubles.update', $match->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <!-- Common columns spanning two rows -->
                        <td rowspan="2">{{ $match->id }}</td>
                        <td rowspan="2">{{ $match->tournament->name ?? 'N/A' }}</td>
                        <td rowspan="2">{{ $match->category->name ?? 'N/A' }}</td>
                        <td rowspan="2">
                            {{ $match->team1Player1->name ?? 'N/A' }} &amp; {{ $match->team1Player2->name ?? 'N/A' }}<br>
                            {{ $match->team2Player1->name ?? 'N/A' }} &amp; {{ $match->team2Player2->name ?? 'N/A' }}
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
                        <!-- First row: Set scores for Team 1 -->
                        <td>
                            <input type="number" name="set1_team1_points" class="form-control" value="{{ $match->set1_team1_points }}" style="width:60px;">
                        </td>
                        <td>
                            <input type="number" name="set2_team1_points" class="form-control" value="{{ $match->set2_team1_points }}" style="width:60px;">
                        </td>
                        <td>
                            <input type="number" name="set3_team1_points" class="form-control" value="{{ $match->set3_team1_points }}" style="width:60px;">
                        </td>
                        <td rowspan="2">
                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                        </td>
                    </form>
                    <!-- Delete form is placed in its own cell outside the update form -->
                    <td rowspan="2">
                        <form action="{{ route('matches.doubles.delete', $match->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this match?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                <tr>
                    <!-- Second row: Set scores for Team 2 -->
                    <td>
                        <input type="number" name="set1_team2_points" class="form-control" value="{{ $match->set1_team2_points }}" style="width:60px;">
                    </td>
                    <td>
                        <input type="number" name="set2_team2_points" class="form-control" value="{{ $match->set2_team2_points }}" style="width:60px;">
                    </td>
                    <td>
                        <input type="number" name="set3_team2_points" class="form-control" value="{{ $match->set3_team2_points }}" style="width:60px;">
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
