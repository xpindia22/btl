@extends('layouts.app')

@section('content')
<div class="container">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <h1 class="text-center">Edit Doubles Matches (BD, GD, XD)</h1>

    <!-- Flash Messages -->
    <div id="flash-message" class="alert text-center d-none" style="position: fixed; top: 10px; right: 10px; z-index: 1000;"></div>

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
                <tr id="match-{{ $match->id }}">
                    <td>{{ $match->id }}</td>
                    <td>{{ $match->tournament->name ?? 'N/A' }}</td>
                    <td>{{ $match->category->name ?? 'N/A' }}</td>
                    <td>{{ $match->team1Player1->name ?? 'N/A' }} & {{ $match->team1Player2->name ?? 'N/A' }}</td>
                    <td>{{ $match->team2Player1->name ?? 'N/A' }} & {{ $match->team2Player2->name ?? 'N/A' }}</td>

                    <!-- Editable Fields -->
                    <td><input type="text" class="editable form-control" data-id="{{ $match->id }}" data-field="stage" value="{{ $match->stage }}"></td>
                    <td><input type="date" class="editable form-control" data-id="{{ $match->id }}" data-field="match_date" value="{{ $match->match_date }}"></td>
                    <td><input type="time" class="editable form-control" data-id="{{ $match->id }}" data-field="match_time" value="{{ $match->match_time }}"></td>

                    <td>
                        <input type="number" class="editable form-control small-input" data-id="{{ $match->id }}" data-field="set1_team1_points" value="{{ $match->set1_team1_points }}"> - 
                        <input type="number" class="editable form-control small-input" data-id="{{ $match->id }}" data-field="set1_team2_points" value="{{ $match->set1_team2_points }}">
                    </td>

                    <td>
                        <input type="number" class="editable form-control small-input" data-id="{{ $match->id }}" data-field="set2_team1_points" value="{{ $match->set2_team1_points }}"> - 
                        <input type="number" class="editable form-control small-input" data-id="{{ $match->id }}" data-field="set2_team2_points" value="{{ $match->set2_team2_points }}">
                    </td>

                    <td>
                        <input type="number" class="editable form-control small-input" data-id="{{ $match->id }}" data-field="set3_team1_points" value="{{ $match->set3_team1_points }}"> - 
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

    // ✅ UPDATE MATCH FUNCTION
    document.querySelectorAll(".update-match").forEach(button => {
        button.addEventListener("click", function() {
            let matchId = this.getAttribute("data-id");
            let row = document.getElementById(`match-${matchId}`);

            let formData = {};
            row.querySelectorAll(".editable").forEach(input => {
                formData[input.getAttribute("data-field")] = input.value;
            });

            fetch(`/matches/doubles/${matchId}`, {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {  
                    showFlashMessage("Match updated successfully!", "success"); 
                } else {
                    showFlashMessage("Update failed: " + (data.message || "Unknown error"), "error");
                }
            })
            .catch(error => {
                console.error("Update Error:", error);
                showFlashMessage("Error updating match. Please try again.", "error");
            });
        });
    });

    // ✅ DELETE MATCH FUNCTION
    document.querySelectorAll(".delete-match").forEach(button => {
        button.addEventListener("click", function() {
            let matchId = this.getAttribute("data-id");
            if (!confirm("Are you sure you want to delete this match?")) return;

            fetch(`/matches/doubles/${matchId}`, {
                method: "DELETE",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showFlashMessage("Match deleted successfully!", "success");
                    document.getElementById(`match-${matchId}`).remove(); // Remove row from table
                } else {
                    showFlashMessage("Failed to delete match: " + (data.message || "Unknown error"), "error");
                }
            })
            .catch(error => {
                console.error("Delete Error:", error);
                showFlashMessage("Error deleting match. Check console logs.", "error");
            });
        });
    });
});

</script>

@endsection
