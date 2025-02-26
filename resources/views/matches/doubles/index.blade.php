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
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($matches as $match)
                    <tr id="match-{{ $match->id }}">
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
                        <td id="winner-{{ $match->id }}">{{ $match->winner ?? 'TBD' }}</td>
                        <td>
                            <button class="btn btn-success btn-sm update-match" data-id="{{ $match->id }}">Edit</button>
                            <button class="btn btn-danger btn-sm delete-match" data-id="{{ $match->id }}">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="d-flex justify-content-center">
    {{ $matches->appends(request()->query())->links('vendor.pagination.default') }}
</div>

<!-- Modal for Editing Doubles Match -->
<div class="modal fade" id="editMatchModal" tabindex="-1" aria-labelledby="editMatchModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editMatchModalLabel">Edit Doubles Match</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editMatchForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_match_id">

                    <label for="edit_stage">Stage:</label>
                    <select id="edit_stage" class="form-control">
                        <option value="Pre Quarter Finals">Pre Quarter Finals</option>
                        <option value="Quarter Finals">Quarter Finals</option>
                        <option value="Semifinals">Semifinals</option>
                        <option value="Finals">Finals</option>
                    </select>

                    <label for="edit_match_date">Match Date:</label>
                    <input type="date" id="edit_match_date" class="form-control" required>

                    <label for="edit_match_time">Match Time:</label>
                    <input type="time" id="edit_match_time" class="form-control" required>

                    <button type="submit" class="btn btn-primary mt-3">Update Match</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Open Edit Modal with Data
    $('.update-match').click(function() {
        let matchId = $(this).data('id');
        
        $.ajax({
            url: `/matches/doubles/${matchId}/edit`,
            type: 'GET',
            success: function(match) {
                $('#edit_match_id').val(match.id);
                $('#edit_stage').val(match.stage);
                $('#edit_match_date').val(match.match_date);
                $('#edit_match_time').val(match.match_time);
                $('#editMatchModal').modal('show');
            },
            error: function(xhr) {
                console.error("Error fetching match:", xhr.responseText);
            }
        });
    });

    // Submit Updated Data
    $('#editMatchForm').submit(function(event) {
        event.preventDefault();

        let matchId = $('#edit_match_id').val();
        let formData = {
            _token: "{{ csrf_token() }}",
            _method: "PUT",
            stage: $('#edit_stage').val(),
            match_date: $('#edit_match_date').val(),
            match_time: $('#edit_match_time').val()
        };

        $.ajax({
            url: `/matches/doubles/${matchId}`,
            type: 'POST',
            data: formData,
            success: function(response) {
                alert('Match Updated Successfully!');
                location.reload();
            },
            error: function(xhr) {
                console.error("Update Error:", xhr.responseText);
            }
        });
    });

    // Delete Match
    $('.delete-match').click(function() {
        let matchId = $(this).data('id');
        if (confirm('Are you sure you want to delete this match?')) {
            $.ajax({
                url: `/matches/doubles/${matchId}`,
                type: 'DELETE',
                data: { _token: "{{ csrf_token() }}" },
                success: function(response) {
                    alert('Match Deleted Successfully!');
                    location.reload();
                },
                error: function(xhr) {
                    console.error("Delete Error:", xhr.responseText);
                }
            });
        }
    });
});
</script>

@endsection
