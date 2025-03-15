@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Insert Singles Match</h1>

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

    {{-- Tournament Lock/Unlock --}}
    <form method="POST" action="{{ route('matches.lockTournament') }}">
    @csrf
        <label for="tournament_id">Select Tournament:</label>
        <select name="tournament_id" required>
            <option value="">Select Tournament</option>
            @foreach($tournaments as $t)
                <option value="{{ $t->id }}" {{ isset($lockedTournament) && $lockedTournament->id == $t->id ? 'selected' : '' }}>
                    {{ $t->name }}
                </option>
            @endforeach
        </select>

        @if(isset($lockedTournament))
            <button type="submit" formaction="{{ route('matches.singles.unlockTournament') }}" class="btn btn-danger">Unlock Tournament</button>
        @else
            <button type="submit" class="btn btn-primary">Lock Tournament</button>
        @endif
    </form>

    @if(isset($lockedTournament))
    {{-- Create Match Form --}}
    <form method="POST" action="{{ route('matches.singles.store') }}">
        @csrf
        <label for="category_id">Category:</label>
<select name="category_id" id="category_id" required>
    <option value="">Select Category</option>
    @if(isset($lockedTournament) && isset($lockedTournament->categories) && count($lockedTournament->categories) > 0)
        @foreach($lockedTournament->categories as $cat)
            <option value="{{ $cat->id }}" data-price="{{ $cat->fee ?? 0 }}">{{ $cat->name }}</option>
        @endforeach
    @else
        <option value="">No categories available</option>
    @endif
</select>


        <label for="is_paid">Is Paid?</label>
        <select name="is_paid" id="is_paid">
            <option value="0">No</option>
            <option value="1">Yes</option>
        </select>

        <label for="match_fee">Match Fee:</label>
        <input type="number" id="match_fee" name="match_fee" value="0" min="0" required>

        <label for="player1_id">Player 1:</label>
        <select name="player1_id" id="player1_id" required>
            <option value="">Select Player 1</option>
        </select>

        <label for="player2_id">Player 2:</label>
        <select name="player2_id" id="player2_id" required>
            <option value="">Select Player 2</option>
        </select>

        <label for="stage">Stage:</label>
        <select name="stage" required>
            <option value="Pre Quarter Finals">Pre Quarter Finals</option>
            <option value="Quarter Finals">Quarter Finals</option>
            <option value="Semifinals">Semifinals</option>
            <option value="Finals">Finals</option>
        </select>

        <label for="match_date">Match Date:</label>
        <input type="date" name="match_date" id="match_date" required>

        <label for="match_time">Match Time:</label>
        <input type="time" name="match_time" id="match_time" required>

        {{-- Set Scores --}}
        <div id="set_scores">
            <label for="set1_player1_points">Set 1 Score (Player 1):</label>
            <input type="number" name="set1_player1_points" min="0" value="0" required>

            <label for="set1_player2_points">Set 1 Score (Player 2):</label>
            <input type="number" name="set1_player2_points" min="0" value="0" required>

            <label for="set2_player1_points">Set 2 Score (Player 1):</label>
            <input type="number" name="set2_player1_points" min="0" value="0" required>

            <label for="set2_player2_points">Set 2 Score (Player 2):</label>
            <input type="number" name="set2_player2_points" min="0" value="0" required>

            <label for="set3_player1_points">Set 3 Score (Player 1):</label>
            <input type="number" name="set3_player1_points" min="0" value="0">

            <label for="set3_player2_points">Set 3 Score (Player 2):</label>
            <input type="number" name="set3_player2_points" min="0" value="0">
        </div>

        <button type="submit" class="btn btn-success">Add Match</button>
    </form>
    @endif
</div>

<script>
// Script for updating match fee
const categoryDropdown = document.getElementById("category_id");
const matchFeeInput = document.getElementById("match_fee");
const isPaidDropdown = document.getElementById("is_paid");

categoryDropdown.addEventListener("change", function () {
    const selectedOption = this.options[this.selectedIndex];
    const price = selectedOption.getAttribute("data-price") || 0;
    matchFeeInput.value = price;
});

isPaidDropdown.addEventListener("change", function () {
    matchFeeInput.disabled = (this.value == "0");
    if (this.value == "0") {
        matchFeeInput.value = 0;
    }
});
</script>

<script>
// Script for fetching players dynamically
const player1Dropdown = document.getElementById("player1_id");
const player2Dropdown = document.getElementById("player2_id");

categoryDropdown.addEventListener("change", function () {
    const categoryId = this.value;
    if (!categoryId) return;

    fetch(`/btl/matches/singles/filtered-players?category_id=${categoryId}`)
        .then(response => response.json())
        .then(players => {
            let playerOptions = "<option value=''>-- Select Player --</option>";
            players.forEach(player => {
                playerOptions += `<option value="${player.id}">${player.name} (Age: ${player.age}, Sex: ${player.sex})</option>`;
            });
            player1Dropdown.innerHTML = playerOptions;
            player2Dropdown.innerHTML = playerOptions;
        })
        .catch(error => console.error("❌ Error fetching players:", error));
});
</script>
@endsection
