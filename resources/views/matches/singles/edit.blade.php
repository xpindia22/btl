@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Singles Matches (Edit Mode)</h1>

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
                <!-- Separate columns for each set/player -->
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
</div>


<script>
document.addEventListener("DOMContentLoaded", function () {
    // Handle UPDATE
    document.querySelectorAll(".update-btn").forEach(button => {
        button.addEventListener("click", function () {
            let matchId = this.dataset.matchId;
            let data = {
                _token: "{{ csrf_token() }}",
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

            // Make a PUT request
            fetch(`/matches/singles/${matchId}/update`, {
                method: "PUT",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(responseData => {
                alert(responseData.message);
                // You can also refresh the table or show a success message, etc.
            })
            .catch(error => console.error("Error:", error));
        });
    });

    // Handle DELETE
    document.querySelectorAll(".delete-btn").forEach(button => {
        button.addEventListener("click", function () {
            let matchId = this.dataset.matchId;
            if (confirm("Are you sure you want to delete this match?")) {
                fetch(`/matches/singles/${matchId}/delete`, {
                    method: "DELETE",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ _token: "{{ csrf_token() }}" })
                })
                .then(response => response.json())
                .then(responseData => {
                    document.getElementById(`match-${matchId}`).remove();
                    alert("Match deleted successfully!");
                })
                .catch(error => console.error("Error:", error));
            }
        });
    });
});
</script>
@endsection
