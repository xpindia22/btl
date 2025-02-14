@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Insert Boys Doubles Match</h1>

    @if(session('message'))
        <p class="alert alert-info">{{ session('message') }}</p>
    @endif

    {{-- Tournament Lock/Unlock Form --}}
    <form method="POST">
        @csrf
        <label for="tournament_id">Select Tournament:</label>
        <select name="tournament_id" id="tournament_id" required {{ $lockedTournament ? 'disabled' : '' }}>
            <option value="">Select Tournament</option>
            @foreach($tournaments as $tournament)
                <option value="{{ $tournament->id }}" {{ (isset($lockedTournament) && $lockedTournament->id == $tournament->id) ? 'selected' : '' }}>
                    {{ $tournament->name }}
                </option>
            @endforeach
        </select>

        @if($lockedTournament)
            <button type="submit" formaction="{{ route('matches.doubles_boys.unlockTournament') }}" id="unlock-btn" style="background-color: red;">Unlock Tournament</button>
        @else
            <button type="submit" formaction="{{ route('matches.doubles_boys.lockTournament') }}" id="lock-btn">Lock Tournament</button>
        @endif
    </form>

    {{-- Form for Adding Matches (Only when Tournament is Locked) --}}
    @if($lockedTournament)
    <form method="POST" action="{{ route('matches.doubles_boys.store') }}">
        @csrf
        <input type="hidden" name="tournament_id" value="{{ $lockedTournament->id }}">

        <label for="category_id">Category:</label>
        <select name="category_id" id="category_id" onchange="updatePlayerDropdown()" required>
            <option value="">Select Category</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" data-sex="{{ $category->sex }}" data-age="{{ $category->age_group }}">
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        @foreach (['team1_player1', 'team1_player2', 'team2_player1', 'team2_player2'] as $player)
            <label for="{{ $player }}_id">{{ ucwords(str_replace('_', ' ', $player)) }}:</label>
            <select name="{{ $player }}_id" id="{{ $player }}_id" required></select>
        @endforeach

        <label for="stage">Match Stage:</label>
        <select name="stage" id="stage" required>
            <option value="Pre Quarter Finals">Pre Quarter Finals</option>
            <option value="Quarter Finals">Quarter Finals</option>
            <option value="Semifinals">Semifinals</option>
            <option value="Finals">Finals</option>
        </select>

        <label for="date">Match Date:</label>
        <input type="date" name="date" id="date" required>

        <!-- Ensure the match time field is correctly named -->
        <label for="match_time">Match Time (24-hour format HH:MM):</label>
        <input type="time" name="time" id="match_time" required>


        @for ($i = 1; $i <= 3; $i++)
            <label for="set{{ $i }}_team1_points">Set {{ $i }} Team 1 Points:</label>
            <input type="number" name="set{{ $i }}_team1_points" id="set{{ $i }}_team1_points">

            <label for="set{{ $i }}_team2_points">Set {{ $i }} Team 2 Points:</label>
            <input type="number" name="set{{ $i }}_team2_points" id="set{{ $i }}_team2_points">
        @endfor

        <button type="submit">Add Match</button>
    </form>
    @endif
</div>

<script>
    const players = @json($players);

    function updatePlayerDropdown() {
        const category = document.getElementById('category_id');
        const playerDropdowns = ['team1_player1_id', 'team1_player2_id', 'team2_player1_id', 'team2_player2_id'];

        const selectedCategory = category.options[category.selectedIndex];
        const categorySex = selectedCategory.getAttribute('data-sex');
        const categoryAge = selectedCategory.getAttribute('data-age');

        let maxAge = 100;
        if (categoryAge && (categoryAge.includes("Under") || categoryAge.includes("Plus") || categoryAge.includes("+"))) {
            maxAge = parseInt(categoryAge.replace(/\D/g, ''), 10);
        }

        playerDropdowns.forEach(selectId => {
            let select = document.getElementById(selectId);
            select.innerHTML = '<option value="">Select Player</option>';
        });

        let filteredPlayers = players.filter(player => player.sex === categorySex && player.age < maxAge);

        playerDropdowns.forEach(selectId => {
            let select = document.getElementById(selectId);
            filteredPlayers.forEach(player => {
                let option = `<option value="${player.id}">${player.name} (${player.age}, ${player.sex})</option>`;
                select.innerHTML += option;
            });
        });

        // Ensure Player 1 & Player 2 are different in the same team
        preventDuplicateSelection('team1_player1_id', 'team1_player2_id');
        preventDuplicateSelection('team2_player1_id', 'team2_player2_id');
    }

    function preventDuplicateSelection(player1, player2) {
        let p1 = document.getElementById(player1);
        let p2 = document.getElementById(player2);

        p1.addEventListener('change', () => {
            filterDropdown(p1, p2);
        });

        p2.addEventListener('change', () => {
            filterDropdown(p2, p1);
        });
    }

    function filterDropdown(selected, target) {
        let selectedValue = selected.value;
        let allOptions = [...target.options];

        target.innerHTML = '<option value="">Select Player</option>';

        allOptions.forEach(option => {
            if (option.value !== selectedValue) {
                target.appendChild(option);
            }
        });
    }
</script>
@endsection
