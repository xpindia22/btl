@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Insert Mixed Doubles Match</h1>
    
    @if(session('message'))
        <p class="message {{ strpos(session('message'), 'success') !== false ? 'success' : 'error' }}">
            {{ session('message') }}
        </p>
    @endif

    @if(!$lockedTournamentId)
        <!-- If no tournament is locked, allow user to select and lock one -->
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
        <!-- Show locked tournament info with an unlock button -->
        <form method="POST" action="{{ route('matches.doubles_mixed.store') }}">
            @csrf
            <p>Locked Tournament: {{ session('locked_tournament_name') ?? 'Unknown' }}</p>
            <button type="submit" name="unlock_tournament">Unlock Tournament</button>
        </form>

        <!-- Match entry form -->
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
                <option value="Pre Quarter Finals">Pre Quarter Finals</option>
                <option value="Quarter Finals">Quarter Finals</option>
                <option value="Semi Finals">Semi Finals</option>
                <option value="Finals">Finals</option>
            </select>

            <label for="date">Match Date:</label>
            <input type="date" name="date" required>

            <label for="time">Match Time:</label>
            <input type="time" name="time" required>

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
            // Filter players by sex
            const femalePlayers = data.filter(player => player.sex === 'F');
            const malePlayers = data.filter(player => player.sex === 'M');
            
            // Get references to dropdown elements
            const team1_player1_select = document.getElementById('team1_player1_id'); // female
            const team1_player2_select = document.getElementById('team1_player2_id'); // male
            const team2_player1_select = document.getElementById('team2_player1_id'); // female
            const team2_player2_select = document.getElementById('team2_player2_id'); // male
            
            // Clear any existing options and add a default option
            [team1_player1_select, team1_player2_select, team2_player1_select, team2_player2_select].forEach(select => {
                select.innerHTML = '<option value="">Select Player</option>';
            });

            // Populate female dropdowns (Team1 Player1 and Team2 Player1)
            femalePlayers.forEach(player => {
                const option = document.createElement('option');
                option.value = player.id;
                option.textContent = `${player.name} (${player.age} years, ${player.sex})`;
                // Append a copy to each dropdown
                team1_player1_select.appendChild(option.cloneNode(true));
                team2_player1_select.appendChild(option);
            });

            // Populate male dropdowns (Team1 Player2 and Team2 Player2)
            malePlayers.forEach(player => {
                const option = document.createElement('option');
                option.value = player.id;
                option.textContent = `${player.name} (${player.age} years, ${player.sex})`;
                // Append a copy to each dropdown
                team1_player2_select.appendChild(option.cloneNode(true));
                team2_player2_select.appendChild(option);
            });
        })
        .catch(error => console.error('Error fetching players:', error));
}
</script>


@endsection
