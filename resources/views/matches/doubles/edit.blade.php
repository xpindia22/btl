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
                <tr data-match-id="{{ $match->id }}">
                    <!-- Common columns -->
                    <td>{{ $match->id }}</td>
                    <td>{{ $match->tournament->name ?? 'N/A' }}</td>
                    <td>{{ $match->category->name ?? 'N/A' }}</td>
                    <td>
                        {{ $match->team1Player1->name ?? 'N/A' }} &amp; {{ $match->team1Player2->name ?? 'N/A' }}<br>
                        {{ $match->team2Player1->name ?? 'N/A' }} &amp; {{ $match->team2Player2->name ?? 'N/A' }}
                    </td>
                    <td>
                        <select name="stage" class="form-control stage" data-match-id="{{ $match->id }}">
                            @foreach(['Pre Quarter Finals', 'Quarter Finals', 'Semifinals', 'Finals'] as $stageOption)
                                <option value="{{ $stageOption }}" {{ $match->stage == $stageOption ? 'selected' : '' }}>
                                    {{ $stageOption }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="date" name="match_date" class="form-control match_date" data-match-id="{{ $match->id }}" value="{{ $match->match_date }}">
                    </td>
                    <td>
                        <input type="time" name="match_time" class="form-control match_time" data-match-id="{{ $match->id }}" value="{{ $match->match_time }}">
                    </td>
                    <!-- First row: Team 1 set scores -->
                    <td>
                        <input type="number" name="set1_team1_points" class="form-control set1_team1_points" data-match-id="{{ $match->id }}" value="{{ $match->set1_team1_points }}" style="width:60px;">
                    </td>
                    <td>
                        <input type="number" name="set2_team1_points" class="form-control set2_team1_points" data-match-id="{{ $match->id }}" value="{{ $match->set2_team1_points }}" style="width:60px;">
                    </td>
                    <td>
                        <input type="number" name="set3_team1_points" class="form-control set3_team1_points" data-match-id="{{ $match->id }}" value="{{ $match->set3_team1_points }}" style="width:60px;">
                    </td>
                    <td rowspan="2">
                        <button type="button" class="btn btn-sm btn-primary update-btn" data-match-id="{{ $match->id }}">Save</button>
                    </td>
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
                <tr data-match-id="{{ $match->id }}">
                    <!-- Second row: Team 2 set scores; leave empty cells to align -->
                    <td colspan="7"></td>
                    <td>
                        <input type="number" name="set1_team2_points" class="form-control set1_team2_points" data-match-id="{{ $match->id }}" value="{{ $match->set1_team2_points }}" style="width:60px;">
                    </td>
                    <td>
                        <input type="number" name="set2_team2_points" class="form-control set2_team2_points" data-match-id="{{ $match->id }}" value="{{ $match->set2_team2_points }}" style="width:60px;">
                    </td>
                    <td>
                        <input type="number" name="set3_team2_points" class="form-control set3_team2_points" data-match-id="{{ $match->id }}" value="{{ $match->set3_team2_points }}" style="width:60px;">
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $matches->appends(request()->query())->links('vendor.pagination.semantic-ui') }}
    </div>
</div>

<!-- Notification container (optional, if you want a dedicated container) -->
<div id="notification-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>

<script>
function showNotification(message, type) {
    // Create a notification element
    let notification = document.createElement('div');
    notification.innerText = message;
    notification.style.padding = '10px 20px';
    notification.style.marginBottom = '10px';
    notification.style.borderRadius = '5px';
    notification.style.color = '#fff';
    notification.style.boxShadow = '0 0 10px rgba(0,0,0,0.3)';
    notification.style.fontWeight = 'bold';
    notification.style.opacity = '0.9';
    
    if (type === 'success') {
        notification.style.backgroundColor = 'green';
    } else {
        notification.style.backgroundColor = 'red';
    }
    
    // Append to container
    let container = document.getElementById('notification-container');
    container.appendChild(notification);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

document.querySelectorAll(".update-btn").forEach(button => {
    button.addEventListener("click", function () {
        let matchId = this.dataset.matchId;
        // Collect values from inputs for both teams using data-match-id selectors
        let payload = {
            stage: document.querySelector(`.stage[data-match-id="${matchId}"]`).value,
            match_date: document.querySelector(`.match_date[data-match-id="${matchId}"]`).value,
            match_time: document.querySelector(`.match_time[data-match-id="${matchId}"]`).value,
            set1_team1_points: document.querySelector(`.set1_team1_points[data-match-id="${matchId}"]`).value,
            set2_team1_points: document.querySelector(`.set2_team1_points[data-match-id="${matchId}"]`).value,
            set3_team1_points: document.querySelector(`.set3_team1_points[data-match-id="${matchId}"]`).value,
            set1_team2_points: document.querySelector(`.set1_team2_points[data-match-id="${matchId}"]`).value,
            set2_team2_points: document.querySelector(`.set2_team2_points[data-match-id="${matchId}"]`).value,
            set3_team2_points: document.querySelector(`.set3_team2_points[data-match-id="${matchId}"]`).value
        };

        console.log("Payload for match " + matchId + ":", payload);

        fetch(`/btl/matches/doubles/${matchId}/update`, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify(payload)
        })
        .then(response => response.json())
        .then(data => {
            console.log("Update response:", data);
            showNotification(data.message, 'success');
            // Optionally update the DOM here if needed; otherwise, reload the page:
            // location.reload();
        })
        .catch(error => {
            console.error("Update failed:", error);
            showNotification("Update failed: " + (error.message || 'Please check your fields.'), 'error');
        });
    });
});
</script>
@endsection
