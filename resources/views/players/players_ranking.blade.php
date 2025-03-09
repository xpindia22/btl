@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Player Rankings</h2>

    <!-- Filters -->
    <form method="GET" action="{{ route('players.ranking') }}" class="mb-4">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Category</label>
                <select class="form-select" name="category_id">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
    
                <label class="form-label">Player</label>
                <select class="form-select" name="player_id">
                    <option value="">All Players</option>
                    @foreach($playersList as $player)
                        <option value="{{ $player->id }}" {{ request('player_id') == $player->id ? 'selected' : '' }}>
                            {{ $player->name }} ({{ $player->uid }})
                        </option>
                    @endforeach
                </select>
        
                <label class="form-label">Date</label>
                <input type="date" class="form-control" name="date" value="{{ request('date') }}">
   
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </div>
    </form>

    <!-- Player Ranking Table -->
    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th># Ranking</th>
                <th>Name</th>
                <th>Age</th>
                <th>Sex</th>
                <th>UID</th>
                <th>Matches Played</th>
                <th>Total Points</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rankings as $player)
                <tr>
                    <td>{{ $player->ranking }}</td>
                    <td>{{ $player->name }}</td>
                    <td>{{ $player->age }}</td>
                    <td>{{ ucfirst($player->sex) }}</td>
                    <td>{{ $player->uid }}</td>
                    <td>{{ $player->matches_played }}</td>
                    <td>{{ $player->total_points }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No players found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination (if applicable) -->
    {{-- Uncomment below if pagination is used --}}
    {{-- {{ $rankings->appends(request()->query())->links() }} --}}
</div>
@endsection
