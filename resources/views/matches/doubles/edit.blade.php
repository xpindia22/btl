@extends('layouts.app')

@section('content')
<div class="container">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <h1 class="text-center">Edit Doubles Matches (BD, GD, XD)</h1>

    <!-- Flash Messages -->
    <div id="flash-message" class="alert text-center d-none"></div>

    
    <!-- Filters Row in One Line -->
    <form method="GET" action="{{ route('matches.doubles.index') }}" class="mb-3">
        <div class="d-flex align-items-center flex-nowrap" style="overflow-x: auto;">
            <div class="form-group mr-3">
                <label for="filter_tournament" class="mr-1">Tournament:</label>
                <select name="filter_tournament" id="filter_tournament" class="form-control" style="width: auto; display: inline-block;">
                    <option value="all" {{ request('filter_tournament', 'all') == 'all' ? 'selected' : '' }}>All</option>
                    @foreach($tournaments as $tournament)
                        <option value="{{ $tournament->id }}" {{ request('filter_tournament') == $tournament->id ? 'selected' : '' }}>
                            {{ $tournament->name }}
                        </option>
                    @endforeach
                </select>
            
                <label for="filter_player" class="mr-1">Player:</label>
                <select name="filter_player" id="filter_player" class="form-control" style="width: auto; display: inline-block;">
                    <option value="all" {{ request('filter_player', 'all') == 'all' ? 'selected' : '' }}>All</option>
                    @foreach($players as $player)
                        <option value="{{ $player->id }}" {{ request('filter_player') == $player->id ? 'selected' : '' }}>
                            {{ $player->name }}
                        </option>
                    @endforeach
                </select>
            
                <label for="filter_category" class="mr-1">Category:</label>
                <select name="filter_category" id="filter_category" class="form-control" style="width: auto; display: inline-block;">
                    <option value="all" {{ request('filter_category', 'all') == 'all' ? 'selected' : '' }}>All</option>
                    <option value="BD" {{ request('filter_category') == 'BD' ? 'selected' : '' }}>Boys Doubles (BD)</option>
                    <option value="GD" {{ request('filter_category') == 'GD' ? 'selected' : '' }}>Girls Doubles (GD)</option>
                    <option value="XD" {{ request('filter_category') == 'XD' ? 'selected' : '' }}>Mixed Doubles (XD)</option>
                </select>
                <label for="filter_date" class="mr-1">Date:</label>
                <input type="date" name="filter_date" id="filter_date" class="form-control" style="width: auto; display: inline-block;" placeholder="dd-mm-yyyy" value="{{ request('filter_date') }}">
            
                <label for="filter_stage" class="mr-1">Stage:</label>
                <select name="filter_stage" id="filter_stage" class="form-control" style="width: auto; display: inline-block;">
                    <option value="all" {{ request('filter_stage', 'all') == 'all' ? 'selected' : '' }}>All</option>
                    <option value="Pre Quarter Finals" {{ request('filter_stage') == 'Pre Quarter Finals' ? 'selected' : '' }}>Pre Quarter Finals</option>
                    <option value="Quarter Finals" {{ request('filter_stage') == 'Quarter Finals' ? 'selected' : '' }}>Quarter Finals</option>
                    <option value="Semifinals" {{ request('filter_stage') == 'Semifinals' ? 'selected' : '' }}>Semifinals</option>
                    <option value="Finals" {{ request('filter_stage') == 'Finals' ? 'selected' : '' }}>Finals</option>
                </select>
            
                <label for="filter_results" class="mr-1">Results:</label>
                <select name="filter_results" id="filter_results" class="form-control" style="width: auto; display: inline-block;">
                    <option value="all" {{ request('filter_results', 'all') == 'all' ? 'selected' : '' }}>All</option>
                    <option value="Team 1" {{ request('filter_results') == 'Team 1' ? 'selected' : '' }}>Team 1 Won</option>
                    <option value="Team 2" {{ request('filter_results') == 'Team 2' ? 'selected' : '' }}>Team 2 Won</option>
                    <option value="Draw" {{ request('filter_results') == 'Draw' ? 'selected' : '' }}>Draw</option>
                </select>
            
                <button type="submit" class="btn btn-primary">Apply Filter</button>
            </div>
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
                    <th>Players</th>
                    <th>Stage</th>
                    <th>Match Date</th>
                    <th>Match Time</th>
                    <th>Sets (Team1 | Team2)</th>
                    <th>Winner</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($matches as $match)
                <tr id="match-{{ $match->id }}">
                    <td rowspan="2">{{ $match->id }}</td>
                    <td rowspan="2">{{ $match->tournament->name ?? 'N/A' }}</td>
                    <td rowspan="2">{{ $match->category->name ?? 'N/A' }}</td>

                    <!-- Team 1 Players -->
                    <td>{{ $match->team1Player1->name ?? 'N/A' }} & {{ $match->team1Player2->name ?? 'N/A' }}</td>

                    <!-- Stage Dropdown -->
                    <td rowspan="2">
                        <select class="editable form-control" data-id="{{ $match->id }}" data-field="stage">
                            <option value="Pre Quarter Finals" {{ $match->stage == 'Pre Quarter Finals' ? 'selected' : '' }}>Pre Quarter Finals</option>
                            <option value="Quarter Finals" {{ $match->stage == 'Quarter Finals' ? 'selected' : '' }}>Quarter Finals</option>
                            <option value="Semifinals" {{ $match->stage == 'Semifinals' ? 'selected' : '' }}>Semifinals</option>
                            <option value="Finals" {{ $match->stage == 'Finals' ? 'selected' : '' }}>Finals</option>
                        </select>
                    </td>

                    <!-- Match Date & Time -->
                    <td rowspan="2">
                        <input type="date" class="editable form-control" data-id="{{ $match->id }}" data-field="match_date" value="{{ $match->match_date }}">
                    </td>
                    <td rowspan="2">
                        <input type="time" class="editable form-control" data-id="{{ $match->id }}" data-field="match_time" value="{{ $match->match_time }}">
                    </td>

                    <!-- Sets (Team1) -->
                    <td>
                        <input type="number" class="editable form-control small-input" data-id="{{ $match->id }}" data-field="set1_team1_points" value="{{ $match->set1_team1_points }}"> | 
                        <input type="number" class="editable form-control small-input" data-id="{{ $match->id }}" data-field="set2_team1_points" value="{{ $match->set2_team1_points }}"> | 
                        <input type="number" class="editable form-control small-input" data-id="{{ $match->id }}" data-field="set3_team1_points" value="{{ $match->set3_team1_points }}">
                    </td>

                    <!-- Winner & Actions -->
                    <td rowspan="2" id="winner-{{ $match->id }}">{{ $match->winner ?? 'TBD' }}</td>
                    <td rowspan="2">
                        <button class="btn btn-success btn-sm update-match" data-id="{{ $match->id }}">Update</button>
                        <button class="btn btn-danger btn-sm delete-match" data-id="{{ $match->id }}">Delete</button>
                    </td>
                </tr>
                <tr>
                    <!-- Team 2 Players -->
                    <td>{{ $match->team2Player1->name ?? 'N/A' }} & {{ $match->team2Player2->name ?? 'N/A' }}</td>

                    <!-- Sets (Team2) -->
                    <td>
                        <input type="number" class="editable form-control small-input" data-id="{{ $match->id }}" data-field="set1_team2_points" value="{{ $match->set1_team2_points }}"> | 
                        <input type="number" class="editable form-control small-input" data-id="{{ $match->id }}" data-field="set2_team2_points" value="{{ $match->set2_team2_points }}"> | 
                        <input type="number" class="editable form-control small-input" data-id="{{ $match->id }}" data-field="set3_team2_points" value="{{ $match->set3_team2_points }}">
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<style>
/* Flash message styling */
#flash-message {
    display: none;
    font-weight: bold;
    position: fixed;
    top: 10px;
    right: 10px;
    z-index: 1000;
    padding: 10px;
    border-radius: 5px;
}
.flash-success {
    background-color: #28a745; /* Green */
    color: white;
}
.flash-error {
    background-color: #ff6b6b; /* Light Red */
    color: white;
}

/* Small input adjustments */
.small-input {
    width: 50px;
    text-align: center;
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    function showFlashMessage(message, type) {
        let flashMessage = document.getElementById("flash-message");
        flashMessage.innerText = message;
        flashMessage.classList.remove("d-none", "flash-success", "flash-error");
        flashMessage.classList.add(type === "success" ? "flash-success" : "flash-error");
        flashMessage.style.display = "block";
        setTimeout(() => { flashMessage.style.display = "none"; }, 3000);
    }

    // UPDATE MATCH FUNCTION
    document.querySelectorAll(".update-match").forEach(button => {
        button.addEventListener("click", async function() {
            let matchId = this.getAttribute("data-id");
            // Select the first row (which has the unique id) and its next sibling (second row)
            let firstRow = document.getElementById(`match-${matchId}`);
            let secondRow = firstRow.nextElementSibling;
            
            let formData = {};
            // Gather inputs from the first row
            firstRow.querySelectorAll("input.editable, select.editable").forEach(input => {
                formData[input.getAttribute("data-field")] = input.value;
            });
            // Gather inputs from the second row (if it exists)
            if (secondRow) {
                secondRow.querySelectorAll("input.editable, select.editable").forEach(input => {
                    formData[input.getAttribute("data-field")] = input.value;
                });
            }

            let csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            console.log("🔄 Updating Match ID:", matchId, "with data:", formData);
            console.log("🔑 CSRF Token:", csrfToken);

            try {
                let response = await fetch(`/matches/doubles/${matchId}`, {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken
                    },
                    body: JSON.stringify(formData)
                });

                // Check if response is valid JSON
                let data;
                try {
                    data = await response.json();
                } catch (jsonError) {
                    console.error("❌ JSON Parse Error:", jsonError);
                    throw new Error("Invalid server response, expected JSON.");
                }

                console.log("✅ Server Response:", data);

                if (data.success) {  
                    showFlashMessage("✅ Match updated successfully!", "success"); 
                } else {
                    console.error("❌ Update Error Response:", data);
                    showFlashMessage("❌ Update failed: " + (data.message || "Unknown error"), "error");
                }
            } catch (fetchError) {
                console.error("❌ Fetch Error:", fetchError);
                showFlashMessage("❌ Error updating match. Check console logs.", "error");
            }
        });
    });

    // DELETE MATCH FUNCTION
    document.querySelectorAll(".delete-match").forEach(button => {
        button.addEventListener("click", async function() {
            let matchId = this.getAttribute("data-id");
            if (!confirm("⚠️ Are you sure you want to delete this match?")) return;

            let csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            console.log("🗑️ Deleting Match ID:", matchId);
            console.log("🔑 CSRF Token:", csrfToken);

            try {
                let response = await fetch(`/matches/doubles/${matchId}`, {
                    method: "DELETE",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken
                    }
                });

                // Check if response is valid JSON
                let data;
                try {
                    data = await response.json();
                } catch (jsonError) {
                    console.error("❌ JSON Parse Error:", jsonError);
                    throw new Error("Invalid server response, expected JSON.");
                }

                console.log("✅ Server Response:", data);

                if (data.success) {
                    showFlashMessage("✅ Match deleted successfully!", "success");
                    document.getElementById(`match-${matchId}`).remove(); // Remove the match rows from the table
                } else {
                    showFlashMessage("❌ Failed to delete match: " + (data.message || "Unknown error"), "error");
                }
            } catch (fetchError) {
                console.error("❌ Delete Fetch Error:", fetchError);
                showFlashMessage("❌ Error deleting match. Check console logs.", "error");
            }
        });
    });
});
</script>
@endsection
