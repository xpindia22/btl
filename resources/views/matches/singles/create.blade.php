@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Insert Singles Match</h1>

    {{-- Display success message --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Display validation errors --}}
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

    {{-- Tournament Lock/Unlock Form --}}
    <form method="POST" action="{{ route('matches.singles.lockTournament') }}">
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
            <button type="submit" formaction="{{ route('matches.singles.unlockTournament') }}" class="btn btn-danger">
                Unlock Tournament
            </button>
        @else
            <button type="submit" name="lock_tournament" class="btn btn-primary">Lock Tournament</button>
        @endif
    </form>

    @if($lockedTournament)
    <form method="POST" action="{{ route('matches.singles.store') }}">
        @csrf
        <input type="hidden" name="tournament_id" value="{{ $lockedTournament->id }}">

        {{-- Category Selection --}}
        <label for="category_id">Category:</label>
        <select name="category_id" id="category_id" onchange="updatePlayerDropdown()" required>
            <option value="">Select Category</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" data-sex="{{ $category->sex }}" data-age="{{ $category->age_group }}">
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        {{-- Player Selections --}}
        <label for="player1_id">Player 1:</label>
        <select name="player1_id" id="player1_id" required></select>

        <label for="player2_id">Player 2:</label>
        <select name="player2_id" id="player2_id" required></select>

        {{-- Match Stage --}}
        <label for="stage">Match Stage:</label>
        <select name="stage" id="stage" required>
            <option value="Pre Quarter Finals">Pre Quarter Finals</option>
            <option value="Quarter Finals">Quarter Finals</option>
            <option value="Semifinals">Semifinals</option>
            <option value="Finals">Finals</option>
        </select>

        {{-- Match Date & Time --}}
        <label for="date">Match Date:</label>
        <input type="date" name="date" id="date" required>

        <label for="match_time">Match Time (24-hour format HH:MM):</label>
        <input type="time" name="match_time" id="match_time" required>

        {{-- Score Inputs --}}
        <label>Set 1:</label>
        <input type="number" name="set1_player1_points" placeholder="Player 1 Score" required>
        <input type="number" name="set1_player2_points" placeholder="Player 2 Score" required>

        <label>Set 2:</label>
        <input type="number" name="set2_player1_points" placeholder="Player 1 Score" required>
        <input type="number" name="set2_player2_points" placeholder="Player 2 Score" required>

        <label>Set 3 (if needed):</label>
        <input type="number" name="set3_player1_points" placeholder="Player 1 Score">
        <input type="number" name="set3_player2_points" placeholder="Player 2 Score">

        <button type="submit" class="btn btn-success">Add Match</button>
    </form>
    @endif
</div>

<script>
    const players = @json($players);

    function updatePlayerDropdown() {
        const category = document.getElementById('category_id');
        const player1Dropdown = document.getElementById('player1_id');
        const player2Dropdown = document.getElementById('player2_id');

        player1Dropdown.innerHTML = '<option value="">Select Player 1</option>';
        player2Dropdown.innerHTML = '<option value="">Select Player 2</option>';

        if (!category.value) return;

        const selectedCategory = category.options[category.selectedIndex];
        const categorySex = selectedCategory.getAttribute('data-sex');
        const categoryAge = selectedCategory.getAttribute('data-age');

        let maxAge = 100;
        if (categoryAge && (categoryAge.includes("Under") || categoryAge.includes("Plus") || categoryAge.includes("+"))) {
            maxAge = parseInt(categoryAge.replace(/\D/g, ''), 10);
        }

        let filteredPlayers = players.filter(player => player.sex === categorySex && player.age <= maxAge);
        filteredPlayers.forEach(player => {
            let option = `<option value="${player.id}">${player.name} (${player.age}, ${player.sex})</option>`;
            player1Dropdown.innerHTML += option;
            player2Dropdown.innerHTML += option;
        });
    }
</script>
@endsection
