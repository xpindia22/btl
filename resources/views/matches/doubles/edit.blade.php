@extends('layouts.app')

@section('content')
<div class="container">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <h1 class="text-center">Edit Doubles Matches (BD, GD, XD)</h1>

    <!-- Flash Messages -->
    <div id="flash-message" class="alert text-center d-none"></div>

    <!-- Filters Row -->
    <form method="GET" action="{{ route('matches.doubles.edit') }}" class="mb-3">
        <div class="d-flex flex-wrap align-items-center gap-2">
            <label for="filter_tournament">Tournament:</label>
            <select name="filter_tournament" id="filter_tournament" class="form-control w-auto">
                <option value="all" {{ request('filter_tournament', 'all') == 'all' ? 'selected' : '' }}>All</option>
                @foreach($tournaments as $tournament)
                    <option value="{{ $tournament->id }}" {{ request('filter_tournament') == $tournament->id ? 'selected' : '' }}>
                        {{ $tournament->name }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary">Apply Filters</button>
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
<!-- Stage Dropdown with Correct ENUM Values -->
<td rowspan="2">
    <select class="editable form-control" data-id="{{ $match->id }}" data-field="stage">
        <option value="Pre Quarter Finals" {{ $match->stage == 'Pre Quarter Finals' ? 'selected' : '' }}>Pre Quarter Finals</option>
        <option value="Quarter Finals" {{ $match->stage == 'Quarter Finals' ? 'selected' : '' }}>Quarter Finals</option>
        <option value="Semifinals" {{ $match->stage == 'Semifinals' ? 'selected' : '' }}>Semifinals</option>
        <option value="Finals" {{ $match->stage == 'Finals' ? 'selected' : '' }}>Finals</option>
    </select>
</td>


                    <!-- Match Date & Time -->
                    <td rowspan="2"><input type="date" class="editable form-control" data-id="{{ $match->id }}" data-field="match_date" value="{{ $match->match_date }}"></td>
                    <td rowspan="2"><input type="time" class="editable form-control" data-id="{{ $match->id }}" data-field="match_time" value="{{ $match->match_time }}"></td>

                    <!-- Sets (Team1 | Team2) -->
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

                    <!-- Sets (Team1 | Team2) -->
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

    // ✅ UPDATE MATCH FUNCTION (Debugging Enabled)
    document.querySelectorAll(".update-match").forEach(button => {
        button.addEventListener("click", async function() {
            let matchId = this.getAttribute("data-id");
            let row = document.getElementById(`match-${matchId}`);

            let formData = {};
            row.querySelectorAll("input.editable, select.editable").forEach(input => {
                formData[input.getAttribute("data-field")] = input.value;
            });

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

                // ⚠️ Check if response is valid JSON
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

    // ✅ DELETE MATCH FUNCTION (Debugging Enabled)
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

                // ⚠️ Check if response is valid JSON
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
                    document.getElementById(`match-${matchId}`).remove(); // Remove row from table
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
