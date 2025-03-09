@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Doubles Matches (BD, GD, XD)</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Filters Form -->
    <form method="GET" action="{{ route('matches.doubles.index') }}" class="mb-3">
        <div class="filter-row">
            <div class="filter-item">
                <label for="filter_tournament">Tournament:</label>
                <select name="filter_tournament" id="filter_tournament" class="form-control">
                    <option value="all">All</option>
                    @foreach($tournaments as $tournament)
                        <option value="{{ $tournament->id }}" {{ request('filter_tournament') == $tournament->id ? 'selected' : '' }}>
                            {{ $tournament->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-item">
                <label for="filter_player">Player:</label>
                <select name="filter_player" id="filter_player" class="form-control">
                    <option value="all">All</option>
                    @foreach($players as $player)
                        <option value="{{ $player->id }}" {{ request('filter_player') == $player->id ? 'selected' : '' }}>
                            {{ $player->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-item">
                <label for="filter_category">Category:</label>
                <select name="filter_category" id="filter_category" class="form-control">
                    <option value="all">All</option>
                    <option value="BD" {{ request('filter_category') == 'BD' ? 'selected' : '' }}>Boys Doubles (BD)</option>
                    <option value="GD" {{ request('filter_category') == 'GD' ? 'selected' : '' }}>Girls Doubles (GD)</option>
                    <option value="XD" {{ request('filter_category') == 'XD' ? 'selected' : '' }}>Mixed Doubles (XD)</option>
                </select>
            </div>

            <div class="filter-item">
                <label for="filter_date">Date:</label>
                <input type="date" name="filter_date" id="filter_date" class="form-control" value="{{ request('filter_date') }}">
            </div>

            <div class="filter-item">
                <label for="filter_stage">Stage:</label>
                <select name="filter_stage" id="filter_stage" class="form-control">
                    <option value="all">All</option>
                    <option value="Pre Quarter Finals">Pre Quarter Finals</option>
                    <option value="Quarter Finals">Quarter Finals</option>
                    <option value="Semifinals">Semifinals</option>
                    <option value="Finals">Finals</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Apply Filters</button>
        </div>
    </form>

    <!-- Matches Table -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Match ID</th>
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
                <th>Favorite</th>
            </tr>
        </thead>
        <tbody>
            @foreach($matches as $match)
            <tr>
                <td>{{ $match->id }}</td>
                <td>{{ $match->tournament->name ?? 'N/A' }}</td>
                <td>{{ $match->category->name ?? 'N/A' }}</td>
                <td>{{ optional($match->team1Player1)->name ?? 'N/A' }} & {{ optional($match->team1Player2)->name ?? 'N/A' }}</td>
                <td>{{ optional($match->team2Player1)->name ?? 'N/A' }} & {{ optional($match->team2Player2)->name ?? 'N/A' }}</td>
                <td>{{ $match->stage }}</td>
                <td>{{ $match->match_date }}</td>
                <td>{{ $match->match_time }}</td>
                <td>{{ $match->set1_team1_points ?? 0 }} - {{ $match->set1_team2_points ?? 0 }}</td>
                <td>{{ $match->set2_team1_points ?? 0 }} - {{ $match->set2_team2_points ?? 0 }}</td>
                <td>{{ $match->set3_team1_points ?? 'N/A' }} - {{ $match->set3_team2_points ?? 'N/A' }}</td>
                <td>
                    @php
                        $team1_sets = ($match->set1_team1_points > $match->set1_team2_points) + ($match->set2_team1_points > $match->set2_team2_points) + ($match->set3_team1_points > $match->set3_team2_points);
                        $team2_sets = ($match->set1_team2_points > $match->set1_team1_points) + ($match->set2_team2_points > $match->set2_team1_points) + ($match->set3_team2_points > $match->set3_team1_points);
                        $winner = $team1_sets > $team2_sets ? 'Team 1' : ($team2_sets > $team1_sets ? 'Team 2' : 'Draw');
                    @endphp
                    {{ $winner }}
                </td>
                <td>
                    <form class="favorite-form" data-id="{{ $match->id }}" data-type="App\Models\Matches">
                        @csrf
                        <button type="button"
                            class="btn btn-sm favorite-btn {{ $match->isFavoritedByUser(auth()->id()) ? 'btn-success' : 'btn-primary' }}">
                            {{ $match->isFavoritedByUser(auth()->id()) ? '⭐ Pinned' : '📌 Pin' }}
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $matches->appends(request()->query())->links('vendor.pagination.default') }}
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".favorite-btn").forEach(button => {
            button.addEventListener("click", function (event) {
                event.preventDefault();

                let buttonElement = this;
                let form = buttonElement.closest(".favorite-form");
                let itemId = form.dataset.id;
                let itemType = form.dataset.type;
                let token = document.querySelector('meta[name="csrf-token"]').getAttribute("content");

                fetch("{{ route('favorites.toggle') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": token
                    },
                    body: JSON.stringify({
                        favoritable_id: itemId,
                        favoritable_type: itemType
                    })
                })
                .then(response => response.json())
                .then(data => {
                    buttonElement.classList.toggle("btn-success", data.status === "pinned");
                    buttonElement.classList.toggle("btn-primary", data.status === "unpinned");
                    buttonElement.innerHTML = data.status === "pinned" ? "⭐ Pinned" : "📌 Pin";
                })
                .catch(error => console.error("Error:", error));
            });
        });
    });
</script>
@endsection
