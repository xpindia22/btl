@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Singles Matches</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Filter Form -->
    <form method="GET" action="{{ route('matches.singles.index') }}">
        <!-- Other filter fields, e.g.: -->
        <label for="tournament_id">Tournament:</label>
        <select name="tournament_id" id="tournament_id">
            <option value="">All Tournaments</option>
            @foreach($tournaments as $tournament)
                <option value="{{ $tournament->id }}" {{ request('tournament_id') == $tournament->id ? 'selected' : '' }}>
                    {{ $tournament->name }}
                </option>
            @endforeach
        </select>

        <!-- Dropdown for singles filter -->
        <label for="singles_filter">Singles Type:</label>
        <select name="singles_filter" id="singles_filter">
            <option value="all" {{ request('singles_filter', 'all') === 'all' ? 'selected' : '' }}>All Singles</option>
            <option value="boys" {{ request('singles_filter') === 'boys' ? 'selected' : '' }}>Boys Singles</option>
            <option value="girls" {{ request('singles_filter') === 'girls' ? 'selected' : '' }}>Girls Singles</option>
        </select>

        <!-- New dropdown for number of records per page -->
        <label for="per_page">Records per page:</label>
        <select name="per_page" id="per_page" onchange="this.form.submit()">
            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
            <option value="200" {{ request('per_page') == 200 ? 'selected' : '' }}>200</option>
        </select>

        <!-- Other filter inputs (if any) -->
        <button type="submit">Filter</button>
    </form>

    <!-- Your table displaying matches -->
    <table class="table">
        <thead>
            <tr>
                <th>Match ID</th>
                <th>Tournament</th>
                <th>Category</th>
                <th>Player 1</th>
                <th>Player 2</th>
                <th>Stage</th>
                <th>Match Date</th>
                <th>Match Time</th>
                <th>Set 1</th>
                <th>Set 2</th>
                <th>Set 3</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($matches as $match)
                <tr>
                    <td>{{ $match->id }}</td>
                    <td>{{ optional($match->tournament)->name ?? 'N/A' }}</td>
                    <td>{{ optional($match->category)->name ?? 'N/A' }}</td>
                    <td>{{ optional($match->player1)->name ?? 'N/A' }}</td>
                    <td>{{ optional($match->player2)->name ?? 'N/A' }}</td>
                    <td>{{ $match->stage }}</td>
                    <td>{{ $match->match_date ? date('d-m-Y', strtotime($match->match_date)) : 'N/A' }}</td>
                    <td>{{ $match->match_time ? date('h:i A', strtotime($match->match_time)) : 'N/A' }}</td>
                    <td>{{ $match->set1_player1_points }} - {{ $match->set1_player2_points }}</td>
                    <td>{{ $match->set2_player1_points }} - {{ $match->set2_player2_points }}</td>
                    <td>{{ $match->set3_player1_points }} - {{ $match->set3_player2_points }}</td>
                    <td>
                        <!-- Edit and Delete links/buttons here -->
                        <a href="{{ route('matches.singles.edit', $match->id) }}">Edit</a>
                        <!-- Delete form, etc. -->
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Render pagination links -->
    <div class="d-flex justify-content-center">
        {{ $matches->appends(request()->query())->links() }}
    </div>
    
</div>
@endsection
