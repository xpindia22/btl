@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">Edit Doubles Matches (BD, GD, XD)</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Filters Row -->
    <form method="GET" action="{{ route('matches.doubles.edit') }}" class="mb-3">
        <div class="d-flex flex-wrap align-items-center gap-2">
            <!-- Tournament Filter -->
            <label for="filter_tournament">Tournament:</label>
            <select name="filter_tournament" id="filter_tournament" class="form-control w-auto">
                <option value="all" {{ request('filter_tournament', 'all') == 'all' ? 'selected' : '' }}>All</option>
                @foreach($tournaments as $tournament)
                    <option value="{{ $tournament->id }}" {{ request('filter_tournament') == $tournament->id ? 'selected' : '' }}>
                        {{ $tournament->name }}
                    </option>
                @endforeach
            </select>

            <!-- Player Filter -->
            <label for="filter_player">Player:</label>
            <select name="filter_player" id="filter_player" class="form-control w-auto">
                <option value="all" {{ request('filter_player', 'all') == 'all' ? 'selected' : '' }}>All</option>
                @foreach($players as $player)
                    <option value="{{ $player->id }}" {{ request('filter_player') == $player->id ? 'selected' : '' }}>
                        {{ $player->name }}
                    </option>
                @endforeach
            </select>

            <!-- Category Filter -->
            <label for="filter_category">Category:</label>
            <select name="filter_category" id="filter_category" class="form-control w-auto">
                <option value="all" {{ request('filter_category', 'all') == 'all' ? 'selected' : '' }}>All</option>
                <option value="BD" {{ request('filter_category') == 'BD' ? 'selected' : '' }}>Boys Doubles (BD)</option>
                <option value="GD" {{ request('filter_category') == 'GD' ? 'selected' : '' }}>Girls Doubles (GD)</option>
                <option value="XD" {{ request('filter_category') == 'XD' ? 'selected' : '' }}>Mixed Doubles (XD)</option>
            </select>

            <!-- Date Filter -->
            <label for="filter_date">Date:</label>
            <input type="date" name="filter_date" id="filter_date" class="form-control w-auto" value="{{ request('filter_date') }}">

            <!-- Stage Filter -->
            <label for="filter_stage">Stage:</label>
            <select name="filter_stage" id="filter_stage" class="form-control w-auto">
                <option value="all" {{ request('filter_stage', 'all') == 'all' ? 'selected' : '' }}>All</option>
                <option value="Pre Quarter Finals" {{ request('filter_stage') == 'Pre Quarter Finals' ? 'selected' : '' }}>Pre Quarter Finals</option>
                <option value="Quarter Finals" {{ request('filter_stage') == 'Quarter Finals' ? 'selected' : '' }}>Quarter Finals</option>
                <option value="Semifinals" {{ request('filter_stage') == 'Semifinals' ? 'selected' : '' }}>Semifinals</option>
                <option value="Finals" {{ request('filter_stage') == 'Finals' ? 'selected' : '' }}>Finals</option>
            </select>

            <button type="submit" class="btn btn-primary">Apply Filters</button>
        </div>
    </form>

    <!-- Responsive Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
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
                    <th>Set 1</th>
                    <th>Set 2</th>
                    <th>Set 3</th>
                    <th>Winner</th>
                    <th style="width: 180px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($matches as $match)
                <tr>
                    <td>{{ $match->id }}</td>
                    <td>{{ $match->tournament->name ?? 'N/A' }}</td>
                    <td>{{ $match->category->name ?? 'N/A' }}</td>
                    <td>{{ $match->team1Player1->name ?? 'N/A' }} & {{ $match->team1Player2->name ?? 'N/A' }}</td>
                    <td>{{ $match->team2Player1->name ?? 'N/A' }} & {{ $match->team2Player2->name ?? 'N/A' }}</td>
                    
                    <!-- Editable Fields -->
                    <td><input type="text" class="editable form-control" data-id="{{ $match->id }}" data-field="stage" value="{{ $match->stage }}"></td>
                    <td><input type="date" class="editable form-control" data-id="{{ $match->id }}" data-field="match_date" value="{{ $match->match_date }}"></td>
                    <td><input type="time" class="editable form-control" data-id="{{ $match->id }}" data-field="match_time" value="{{ $match->match_time }}"></td>

                    <td><input type="number" class="editable form-control small-input" data-id="{{ $match->id }}" data-field="set1_team1_points" value="{{ $match->set1_team1_points }}"> - 
                        <input type="number" class="editable form-control small-input" data-id="{{ $match->id }}" data-field="set1_team2_points" value="{{ $match->set1_team2_points }}">
                    </td>
                    
                    <td><input type="number" class="editable form-control small-input" data-id="{{ $match->id }}" data-field="set2_team1_points" value="{{ $match->set2_team1_points }}"> - 
                        <input type="number" class="editable form-control small-input" data-id="{{ $match->id }}" data-field="set2_team2_points" value="{{ $match->set2_team2_points }}">
                    </td>

                    <td><input type="number" class="editable form-control small-input" data-id="{{ $match->id }}" data-field="set3_team1_points" value="{{ $match->set3_team1_points }}"> - 
                        <input type="number" class="editable form-control small-input" data-id="{{ $match->id }}" data-field="set3_team2_points" value="{{ $match->set3_team2_points }}">
                    </td>

                    <td id="winner-{{ $match->id }}">{{ $match->winner ?? 'TBD' }}</td>
                    
                    <!-- Actions -->
                    <td class="text-center">
                        <button class="btn btn-success btn-sm update-match" data-id="{{ $match->id }}">Update</button>
                        <button class="btn btn-danger btn-sm delete-match" data-id="{{ $match->id }}">Delete</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center">
        {{ $matches->withQueryString()->links() }}
    </div>
</div>

<style>
/* Make the table fit the screen */
.table-responsive {
    overflow-x: auto;
}

/* Adjust input field size */
.small-input {
    width: 50px;
    text-align: center;
}

/* Button spacing */
.btn-sm {
    margin: 3px;
}
</style>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Handle Update Button Click
    document.querySelectorAll(".update-match").forEach((button) => {
        button.addEventListener("click", function() {
            let matchId = this.getAttribute("data-id");

            // Select the row of the current match
            let row = this.closest("tr");

            // Extract values from input fields in the row
            let formData = {
                stage: row.querySelector(`[data-field="stage"]`).value,
                match_date: row.querySelector(`[data-field="match_date"]`).value,
                match_time: row.querySelector(`[data-field="match_time"]`).value,
                set1_team1_points: row.querySelector(`[data-field="set1_team1_points"]`).value || null,
                set1_team2_points: row.querySelector(`[data-field="set1_team2_points"]`).value || null,
                set2_team1_points: row.querySelector(`[data-field="set2_team1_points"]`).value || null,
                set2_team2_points: row.querySelector(`[data-field="set2_team2_points"]`).value || null,
                set3_team1_points: row.querySelector(`[data-field="set3_team1_points"]`).value || null,
                set3_team2_points: row.querySelector(`[data-field="set3_team2_points"]`).value || null
            };

            fetch(`/matches/doubles/update/${matchId}`, {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Match updated successfully!");
                    document.getElementById(`winner-${matchId}`).innerText = data.winner ?? "TBD";
                } else {
                    alert("Failed to update match.");
                }
            })
            .catch(error => console.error("Error:", error));
        });
    });
});


</script>

@endsection
