@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Singles Matches (View-Only)</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <style>
        .filter-row {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.5rem;
        }
        .filter-item {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            flex: 1 1 150px;
        }
        .filter-item label {
            white-space: nowrap;
            font-size: 0.9rem;
            margin-bottom: 0;
        }
        .filter-item select,
        .filter-item input {
            flex: 1;
            font-size: 0.9rem;
            padding: 0.25rem 0.5rem;
        }
    </style>

    <form method="GET" action="{{ route('matches.singles.index') }}" class="mb-3">
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
                <label for="filter_player1">Player 1:</label>
                <select name="filter_player1" id="filter_player1" class="form-control">
                    <option value="all">All</option>
                    @foreach($players as $player)
                        <option value="{{ $player->id }}" {{ request('filter_player1') == $player->id ? 'selected' : '' }}>
                            {{ $player->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-item">
                <label for="filter_player2">Player 2:</label>
                <select name="filter_player2" id="filter_player2" class="form-control">
                    <option value="all">All</option>
                    @foreach($players as $player)
                        <option value="{{ $player->id }}" {{ request('filter_player2') == $player->id ? 'selected' : '' }}>
                            {{ $player->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-item">
                <label for="filter_category">Category:</label>
                <select name="filter_category" id="filter_category" class="form-control">
                    <option value="all">All</option>
                    <option value="%BS%">Boys Singles (BS)</option>
                    <option value="%GS%">Girls Singles (GS)</option>
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

    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Tournament</th>
                <th>Category</th>
                <th>Player 1</th>
                <th>Player 2</th>
                <th>Stage</th>
                <th>Date</th>
                <th>Time</th>
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
                    <td>{{ $match->id }}</td> <!-- ✅ Corrected Match ID Display -->
                    <td>{{ optional($match->tournament)->name ?? 'N/A' }}</td>
                    <td>{{ optional($match->category)->name ?? 'N/A' }}</td>
                    <td>{{ optional($match->player1)->name ?? 'N/A' }}</td>
                    <td>{{ optional($match->player2)->name ?? 'N/A' }}</td>
                    <td>{{ $match->stage ?? 'N/A' }}</td>
                    <td>{{ $match->match_date ?? 'N/A' }}</td>
                    <td>{{ $match->match_time ?? 'N/A' }}</td>
                    <td>{{ $match->set1_player1_points ?? 0 }} - {{ $match->set1_player2_points ?? 0 }}</td>
                    <td>{{ $match->set2_player1_points ?? 0 }} - {{ $match->set2_player2_points ?? 0 }}</td>
                    <td>
                        @if($match->set3_player1_points !== null && $match->set3_player2_points !== null)
                            {{ $match->set3_player1_points }} - {{ $match->set3_player2_points }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        @php
                            $p1_sets = ($match->set1_player1_points > $match->set1_player2_points) + ($match->set2_player1_points > $match->set2_player2_points) + ($match->set3_player1_points > $match->set3_player2_points);
                            $p2_sets = ($match->set1_player2_points > $match->set1_player1_points) + ($match->set2_player2_points > $match->set2_player1_points) + ($match->set3_player2_points > $match->set3_player1_points);
                            $winner = $p1_sets > $p2_sets ? optional($match->player1)->name : ($p2_sets > $p1_sets ? optional($match->player2)->name : 'Draw');
                        @endphp
                        {{ $winner }}
                    </td>
                    <td>
                        <button type="button"
                            class="btn btn-sm favorite-btn {{ $match->isFavoritedByUser(auth()->id()) ? 'btn-success' : 'btn-primary' }}"
                            data-id="{{ $match->id }}">
                            {{ $match->isFavoritedByUser(auth()->id()) ? '⭐ Pinned' : '📌 Pin' }}
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="d-flex justify-content-center">
    {{ $matches->appends(request()->query())->links('vendor.pagination.semantic-ui') }}
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".favorite-btn").forEach(button => {
            button.addEventListener("click", function (event) {
                event.preventDefault();

                let buttonElement = this;
                let matchId = buttonElement.dataset.id;
                let token = document.querySelector('meta[name="csrf-token"]').getAttribute("content");

                fetch("{{ route('favorites.toggle') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": token
                    },
                    body: JSON.stringify({ favoritable_id: matchId, favoritable_type: "App\\Models\\Matches" })
                })
                .then(response => response.json())
                .then(data => {
                    buttonElement.classList.toggle("btn-success", data.status === "pinned");
                    buttonElement.classList.toggle("btn-primary", data.status === "unpinned");
                    buttonElement.innerHTML = data.status === "pinned" ? "⭐ Pinned" : "📌 Pin";
                });
            });
        });
    });
</script>

@endsection
