@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">Doubles Matches (BD, GD, XD)</h1>

    @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    <!-- Filters Row -->
    <form method="GET" action="{{ route('matches.doubles.index') }}" class="mb-3">
        <div class="d-flex flex-wrap gap-2">
            <label for="filter_tournament">Tournament:</label>
            <select name="filter_tournament" id="filter_tournament" class="form-control w-auto" onchange="this.form.submit()">
                <option value="all" {{ request('filter_tournament', 'all') == 'all' ? 'selected' : '' }}>All</option>
                @foreach($tournaments as $tournament)
                    <option value="{{ $tournament->id }}" {{ request('filter_tournament') == $tournament->id ? 'selected' : '' }}>
                        {{ $tournament->name }}
                    </option>
                @endforeach
            </select>

            <label for="filter_category">Category:</label>
            <select name="filter_category" id="filter_category" class="form-control w-auto" onchange="this.form.submit()">
                <option value="all" {{ request('filter_category', 'all') == 'all' ? 'selected' : '' }}>All</option>
                <option value="BD" {{ request('filter_category') == 'BD' ? 'selected' : '' }}>Boys Doubles (BD)</option>
                <option value="GD" {{ request('filter_category') == 'GD' ? 'selected' : '' }}>Girls Doubles (GD)</option>
                <option value="XD" {{ request('filter_category') == 'XD' ? 'selected' : '' }}>Mixed Doubles (XD)</option>
            </select>
        </div>
    </form>

    <!-- Matches Table -->
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
                    <th>Match Date</th>
                    <th>Match Time</th>
                    <th>Winner</th>
                </tr>
            </thead>
            <tbody>
                @foreach($matches as $match)
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
                        <td>{{ $match->match_date ?? 'N/A' }}</td>
                        <td>{{ $match->match_time ?? 'N/A' }}</td>
                        <td>{{ $match->winner ?? 'TBD' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="d-flex justify-content-center">
    {{ $matches->appends(request()->query())->links('vendor.pagination.default') }}
</div>
@endsection
