@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Insert Girls Doubles Match</h1>
    @if(session('message'))
        <p>{{ session('message') }}</p>
    @endif

    @if(!$lockedTournament)
        <form method="POST" action="{{ route('matches.doubles_girls.store') }}">
            @csrf
            <label for="tournament_id">Select Tournament:</label>
            <select name="tournament_id" id="tournament_id" required>
                <option value="">Select Tournament</option>
                @foreach($tournaments as $tournament)
                    <option value="{{ $tournament->id }}">{{ $tournament->name }}</option>
                @endforeach
            </select>
            <button type="submit" name="lock_tournament">Lock Tournament</button>
        </form>
    @else
        <form method="POST" action="{{ route('matches.doubles_girls.store') }}">
            @csrf
            <p>Locked Tournament: {{ session('locked_tournament_name') ?? 'Unknown' }}</p>
            <button type="submit" name="unlock_tournament">Unlock Tournament</button>
        </form>

        <form method="POST" action="{{ route('matches.doubles_girls.store') }}">
            @csrf
            <label for="category_id">Category:</label>
            <select name="category_id" id="category_id" required onchange="loadPlayers(this.value)">
                <option value="">Select Category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">
                        {{ $category->name }} ({{ $category->age_group }}, {{ $category->sex }})
                    </option>
                @endforeach
            </select>

            <label for="team1_player1_id">Team 1 - Player 1:</label>
            <select name="team1_player1_id" id="team1_player1_id" required></select>

            <label for="team1_player2_id">Team 1 - Player 2:</label>
            <select name="team1_player2_id" id="team1_player2_id" required></select>

            <label for="team2_player1_id">Team 2 - Player 1:</label>
            <select name="team2_player1_id" id="team2_player1_id" required></select>

            <label for="team2_player2_id">Team 2 - Player 2:</label>
            <select name="team2_player2_id" id="team2_player2_id" required></select>

            <label for="stage">Match Stage:</label>
            <select name="stage" id="stage" required>
                <option value="">Select Stage</option>
                <option value="Pre Quarter Finals">Pre Quarter Finals</option>
                <option value="Quarter Finals">Quarter Finals</option>
                <option value="Semi Finals">Semi Finals</option>
                <option value="Finals">Finals</option>
            </select>

            <label for="date">Match Date:</label>
            <input type="date" name="date" id="date" required>

            <label for="time">Match Time:</label>
            <input type="time" name="time" id="time" required>

            <label for="set1_team1_points">Set 1 Team 1 Points:</label>
            <input type="number" name="set1_team1_points" id="set1_team1_points" required>

            <label for="set1_team2_points">Set 1 Team 2 Points:</label>
            <input type="number" name="set1_team2_points" id="set1_team2_points" required>

            <label for="set2_team1_points">Set 2 Team 1 Points:</label>
            <input type="number" name="set2_team1_points" id="set2_team1_points" required>

            <label for="set2_team2_points">Set 2 Team 2 Points:</label>
            <input type="number" name="set2_team2_points" id="set2_team2_points" required>

            <label for="set3_team1_points">Set 3 Team 1 Points:</label>
            <input type="number" name="set3_team1_points" id="set3_team1_points">

            <label for="set3_team2_points">Set 3 Team 2 Points:</label>
            <input type="number" name="set3_team2_points" id="set3_team2_points">

            <button type="submit">Add Match</button>
        </form>
    @endif
</div>

<script>
function loadPlayers(categoryId) {
    if (!categoryId) return;

    fetch(`/get_players.php?category_id=${categoryId}`)
        .then(response => response.json())
        .then(data => {
            const playerSelects = ['team1_player1_id', 'team1_player2_id', 'team2_player1_id', 'team2_player2_id'];
            playerSelects.forEach(selectId => {
                const select = document.getElementById(selectId);
                select.innerHTML = '<option value="">Select Player</option>';
                data.forEach(player => {
                    const option = document.createElement('option');
                    option.value = player.id;
                    option.textContent = `${player.name} (${player.age} years, ${player.sex})`;
                    select.appendChild(option);
                });
            });
        })
        .catch(error => console.error('Error fetching players:', error));
}
</script>
@endsection
