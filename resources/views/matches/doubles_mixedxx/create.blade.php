@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Insert Mixed Doubles Match</h1>
    
    @if(session('message'))
        <p class="alert alert-info">
            {{ session('message') }}
        </p>
    @endif

    @if(!$lockedTournamentId)
        <!-- Tournament Selection Form -->
        <form method="POST" action="{{ route('matches.doubles_mixed.store') }}">
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
        <!-- Locked Tournament Display with Unlock Button -->
        <p><strong>Locked Tournament:</strong> {{ $lockedTournamentName }}</p>
        <form method="POST" action="{{ route('matches.doubles_mixed.store') }}">
            @csrf
            <button type="submit" name="unlock_tournament">Unlock Tournament</button>
        </form>

        <!-- Match Entry Form -->
        <form method="POST" action="{{ route('matches.doubles_mixed.store') }}">
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
                @foreach($stages as $stage)
                    <option value="{{ $stage }}">{{ $stage }}</option>
                @endforeach
            </select>

            <label for="date">Match Date:</label>
            <input type="date" name="date" required>

            <label for="time">Match Time:</label>
            <input type="time" name="time" required>

            <h3>Set Points</h3>

            <label for="set1_team1_points">Set 1 Team 1 Points:</label>
            <input type="number" name="set1_team1_points" required>

            <label for="set1_team2_points">Set 1 Team 2 Points:</label>
            <input type="number" name="set1_team2_points" required>

            <label for="set2_team1_points">Set 2 Team 1 Points:</label>
            <input type="number" name="set2_team1_points" required>

            <label for="set2_team2_points">Set 2 Team 2 Points:</label>
            <input type="number" name="set2_team2_points" required>

            <label for="set3_team1_points">Set 3 Team 1 Points:</label>
            <input type="number" name="set3_team1_points">

            <label for="set3_team2_points">Set 3 Team 2 Points:</label>
            <input type="number" name="set3_team2_points">

            <button type="submit">Add Match</button>
        </form>
    @endif
</div>

<script>
function loadPlayers(categoryId) {
    if (!categoryId) return;
    fetch(`{{ route('get_players') }}?category_id=${categoryId}`)
        .then(response => response.json())
        .then(data => {
            // Filter players by gender
            const femalePlayers = data.filter(player => player.sex === 'F');
            const malePlayers = data.filter(player => player.sex === 'M');
            
            // Get references to dropdown elements
            const team1_player1_select = document.getElementById('team1_player1_id'); // Female
            const team1_player2_select = document.getElementById('team1_player2_id'); // Male
            const team2_player1_select = document.getElementById('team2_player1_id'); // Female
            const team2_player2_select = document.getElementById('team2_player2_id'); // Male
            
            // Clear any existing options and add a default option
            [team1_player1_select, team1_player2_select, team2_player1_select, team2_player2_select].forEach(select => {
                select.innerHTML = '<option value="">Select Player</option>';
            });

            // Populate female dropdowns (Team1 Player1 and Team2 Player1)
            femalePlayers.forEach(player => {
                const option = document.createElement('option');
                option.value = player.id;
                option.textContent = `${player.name} (${player.age} years, ${player.sex})`;
                team1_player1_select.appendChild(option.cloneNode(true));
                team2_player1_select.appendChild(option);
            });

            // Populate male dropdowns (Team1 Player2 and Team2 Player2)
            malePlayers.forEach(player => {
                const option = document.createElement('option');
                option.value = player.id;
                option.textContent = `${player.name} (${player.age} years, ${player.sex})`;
                team1_player2_select.appendChild(option.cloneNode(true));
                team2_player2_select.appendChild(option);
            });
        })
        .catch(error => console.error('Error fetching players:', error));
}
</script>

@endsection
