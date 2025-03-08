@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Singles Matches (Inline Edit Mode)</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> Please fix the following errors:
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Tournament</th>
                <th>Category</th>
                <th>Player 1</th>
                <th>Player 2</th>
                <th>Stage</th>
                <th>Set 1 (P1)</th>
                <th>Set 1 (P2)</th>
                <th>Set 2 (P1)</th>
                <th>Set 2 (P2)</th>
                <th>Set 3 (P1)</th>
                <th>Set 3 (P2)</th>
                <th>Date</th>
                <th>Time</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($matches as $index => $match)
                <tr id="match-{{ $match->id }}">
                    <td>{{ $index + 1 }}</td>
                    <td>{{ optional($match->tournament)->name ?? 'N/A' }}</td>
                    <td>{{ optional($match->category)->name ?? 'N/A' }}</td>
                    <td>{{ optional($match->player1)->name ?? 'N/A' }}</td>
                    <td>{{ optional($match->player2)->name ?? 'N/A' }}</td>
                    
                    <!-- Stage -->
                    <td>
                        <select class="form-control stage" data-match-id="{{ $match->id }}">
                            @foreach(['Pre Quarter Finals','Quarter Finals','Semifinals','Finals'] as $stage)
                                <option value="{{ $stage }}" {{ $match->stage == $stage ? 'selected' : '' }}>
                                    {{ $stage }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    
                    <!-- Set 1 -->
                    <td>
                        <input type="number" 
                               class="form-control set1_player1_points" 
                               data-match-id="{{ $match->id }}"
                               value="{{ $match->set1_player1_points ?? '' }}">
                    </td>
                    <td>
                        <input type="number" 
                               class="form-control set1_player2_points" 
                               data-match-id="{{ $match->id }}"
                               value="{{ $match->set1_player2_points ?? '' }}">
                    </td>
                    
                    <!-- Set 2 -->
                    <td>
                        <input type="number" 
                               class="form-control set2_player1_points" 
                               data-match-id="{{ $match->id }}"
                               value="{{ $match->set2_player1_points ?? '' }}">
                    </td>
                    <td>
                        <input type="number" 
                               class="form-control set2_player2_points" 
                               data-match-id="{{ $match->id }}"
                               value="{{ $match->set2_player2_points ?? '' }}">
                    </td>
                    
                    <!-- Set 3 -->
                    <td>
                        <input type="number" 
                               class="form-control set3_player1_points" 
                               data-match-id="{{ $match->id }}"
                               value="{{ $match->set3_player1_points ?? '' }}">
                    </td>
                    <td>
                        <input type="number" 
                               class="form-control set3_player2_points" 
                               data-match-id="{{ $match->id }}"
                               value="{{ $match->set3_player2_points ?? '' }}">
                    </td>
                    
                    <!-- Date -->
                    <td>
                        <input type="date" 
                               class="form-control match_date" 
                               data-match-id="{{ $match->id }}"
                               value="{{ $match->match_date }}">
                    </td>
                    
                    <!-- Time -->
                    <td>
                        <input type="time" 
                               class="form-control match_time" 
                               data-match-id="{{ $match->id }}"
                               value="{{ $match->match_time }}">
                    </td>
                    
                    <!-- Actions: Update & Delete -->
                    <td>
                        <button class="btn btn-sm btn-primary update-btn" data-match-id="{{ $match->id }}">
                            Update
                        </button>
                        <button class="btn btn-sm btn-danger delete-btn" data-match-id="{{ $match->id }}">
                            Delete
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <!-- Pagination Links -->
    <div class="d-flex justify-content-center">
        {{ $matches->appends(request()->query())->links('vendor.pagination.default') }}
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Handle UPDATE using fetch with PUT method
    document.querySelectorAll(".update-btn").forEach(button => {
        button.addEventListener("click", function () {
            let matchId = this.dataset.matchId;
            let data = {
                stage: document.querySelector(`.stage[data-match-id="${matchId}"]`).value,
                match_date: document.querySelector(`.match_date[data-match-id="${matchId}"]`).value,
                match_time: document.querySelector(`.match_time[data-match-id="${matchId}"]`).value,
                set1_player1_points: document.querySelector(`.set1_player1_points[data-match-id="${matchId}"]`).value,
                set1_player2_points: document.querySelector(`.set1_player2_points[data-match-id="${matchId}"]`).value,
                set2_player1_points: document.querySelector(`.set2_player1_points[data-match-id="${matchId}"]`).value,
                set2_player2_points: document.querySelector(`.set2_player2_points[data-match-id="${matchId}"]`).value,
                set3_player1_points: document.querySelector(`.set3_player1_points[data-match-id="${matchId}"]`).value,
                set3_player2_points: document.querySelector(`.set3_player2_points[data-match-id="${matchId}"]`).value
            };

            fetch(`/btl/matches/singles/${matchId}/update`, {
                method: "PUT", // Use PUT method directly
                headers: { 
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                console.log("Update response status:", response.status);
                if (!response.ok) {
                    return response.text().then(text => {
                        console.log("Update response text:", text);
                        throw new Error("Update failed. See console for details.");
                    });
                }
                return response.json();
            })
            .then(responseData => {
                console.log("Update success:", responseData);
                alert(responseData.message);
            })
            .catch(error => {
                console.error("Error during update:", error);
                alert("An error occurred while updating the match: " + error.message);
            });
        });
    });

    // Handle DELETE using fetch with DELETE method directly
    document.querySelectorAll(".delete-btn").forEach(button => {
        button.addEventListener("click", function () {
            let matchId = this.dataset.matchId;
            if (confirm("Are you sure you want to delete this match?")) {
                fetch(`/btl/matches/singles/${matchId}/delete`, {
                    method: "DELETE", // Use DELETE method directly
                    headers: { 
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json"
                    },
                })
                .then(response => {
                    console.log("Delete response status:", response.status);
                    if (!response.ok) {
                        return response.text().then(text => {
                            console.log("Delete response text:", text);
                            throw new Error("Delete failed. See console for details.");
                        });
                    }
                    return response.json();
                })
                .then(responseData => {
                    console.log("Delete success:", responseData);
                    document.getElementById(`match-${matchId}`).remove();
                    alert(responseData.message);
                })
                .catch(error => {
                    console.error("Error during delete:", error);
                    alert("An error occurred while deleting the match: " + error.message);
                });
            }
        });
    });
});
</script>

@endsection
