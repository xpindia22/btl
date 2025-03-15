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
    @if(isset($lockedTournament) && $lockedTournament->categories && $lockedTournament->categories->count() > 0)
        @foreach($lockedTournament->categories as $cat)
            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
        @endforeach
    @else
        <option value="" disabled>No categories available</option>
    @endif
</select>



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

        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const now = new Date();
                document.getElementById('match_date').valueAsDate = now;
                document.getElementById('match_time').value = now.toTimeString().slice(0, 5);
            });
        </script>

        {{-- Set Scores --}}
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

        <button type="submit" class="btn btn-success">Add Match</button>
    </form>
    @endif
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const categoryDropdown = document.getElementById("category_id");
    const player1Dropdown = document.getElementById("player1_id");
    const player2Dropdown = document.getElementById("player2_id");

    categoryDropdown.addEventListener("change", function () {
        const categoryId = this.value;
        console.log("🔍 Selected Category ID:", categoryId);

        if (!categoryId) {
            player1Dropdown.innerHTML = "<option value=''>-- Select Player 1 --</option>";
            player2Dropdown.innerHTML = "<option value=''>-- Select Player 2 --</option>";
            return;
        }

        fetch(`/btl/matches/singles/filtered-players?category_id=${categoryId}`)
            .then(response => {
                console.log("🔍 AJAX Response Status:", response.status);
                return response.json();
            })
            .then(players => {
                console.log("✅ Players Received:", players);

                if (!players.length) {
                    alert("⚠️ No players found for this category!");
                }

                let playerOptions = "<option value=''>-- Select Player --</option>";
                players.forEach(player => {
                    playerOptions += `<option value="${player.id}">${player.name} (Age: ${player.age}, Sex: ${player.sex})</option>`;
                });

                player1Dropdown.innerHTML = playerOptions;
                player2Dropdown.innerHTML = playerOptions;

                console.log("✅ Dropdown Updated!");
            })
            .catch(error => {
                console.error("❌ Error fetching players:", error);
                alert("⚠️ Error fetching players. Check console for details.");
            });
    });
});
</script>
@endsection
