@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create Doubles Match (BD, GD, or XD)</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
        </div>
    @endif

    {{-- Championship Lock/Unlock Section --}}
    <form method="POST" action="{{ route('matches.doubles.lockTournament') }}">
        @csrf
        <label for="tournament_id">Select Championship:</label>
        <select name="tournament_id" id="tournament_id" {{ isset($lockedTournament) ? 'disabled' : '' }} required>
            <option value="">Select Championship</option>
            @foreach($tournaments as $tournament)
                <option value="{{ $tournament->id }}" {{ isset($lockedTournament) && $lockedTournament->id == $tournament->id ? 'selected' : '' }}>
                    {{ $tournament->name }}
                </option>
            @endforeach
        </select>

        @if(isset($lockedTournament))
            <button type="submit" formaction="{{ route('matches.doubles.unlockTournament') }}" class="btn btn-danger">Unlock Championship</button>
        @else
            <button type="submit" class="btn btn-primary">Lock Championship</button>
        @endif
    </form>

    @if(isset($lockedTournament))
    <form id="doublesForm" method="POST" action="{{ route('matches.doubles.store') }}">
        @csrf

        <input type="hidden" name="tournament_id" value="{{ $lockedTournament->id }}">

        <!-- Category Dropdown (BD, GD, XD only) -->
        <label for="category_id">Category:</label>
        <select name="category_id" id="category_id" required>
           <option value="">Select Category</option>
           @foreach($categories as $cat)
             @if(strpos($cat->name, 'BD') !== false || strpos($cat->name, 'GD') !== false || strpos($cat->name, 'XD') !== false)
                 <option value="{{ $cat->id }}">{{ $cat->name }}</option>
             @endif
           @endforeach
        </select>

        <!-- Player Selection -->
        <div id="player-selection">
            <h5>Team 1</h5>
            <label>Player 1 (Male):</label>
            <select name="team1_player1_id" id="team1_player1_id" required></select>

            <label>Player 2 (Female):</label>
            <select name="team1_player2_id" id="team1_player2_id" required></select>

            <h5>Team 2</h5>
            <label>Player 3 (Male):</label>
            <select name="team2_player1_id" id="team2_player1_id" required></select>

            <label>Player 4 (Female):</label>
            <select name="team2_player2_id" id="team2_player2_id" required></select>
        </div>

        <!-- Match Details -->
        <label for="stage">Stage:</label>
        <select name="stage" required>
            <option value="Pre Quarter Finals">Pre Quarter Finals</option>
            <option value="Quarter Finals">Quarter Finals</option>
            <option value="Semifinals">Semifinals</option>
            <option value="Finals">Finals</option>
        </select>

        <label for="match_date">Match Date:</label>
        <input type="date" name="match_date" required>

        <label for="match_time">Match Time:</label>
        <input type="time" name="match_time" required>

        <!-- Set Scores -->
        <h4>Set Scores</h4>
        <div class="row">
            <div class="col-md-6">
                <h5>Team 1</h5>
                <label for="set1_team1">Set 1:</label>
                <input type="number" name="set1_team1" required>

                <label for="set2_team1">Set 2:</label>
                <input type="number" name="set2_team1" required>

                <label for="set3_team1">Set 3:</label>
                <input type="number" name="set3_team1">
            </div>
            <div class="col-md-6">
                <h5>Team 2</h5>
                <label for="set1_team2">Set 1:</label>
                <input type="number" name="set1_team2" required>

                <label for="set2_team2">Set 2:</label>
                <input type="number" name="set2_team2" required>

                <label for="set3_team2">Set 3:</label>
                <input type="number" name="set3_team2">
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Create Doubles Match</button>
    </form>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('category_id');

    // When category changes, fetch players
    categorySelect.addEventListener('change', function() {
        const categoryId = this.value;
        if (!categoryId) {
            resetPlayerDropdowns();
            return;
        }

        fetch("{{ route('matches.doubles.filteredPlayers') }}?category_id=" + categoryId)
            .then(response => response.json())
            .then(players => updatePlayerDropdowns(players, categoryId))
            .catch(error => console.error("Error fetching players:", error));
    });

    function resetPlayerDropdowns() {
        document.getElementById('team1_player1_id').innerHTML = '<option value="">Select Player</option>';
        document.getElementById('team1_player2_id').innerHTML = '<option value="">Select Player</option>';
        document.getElementById('team2_player1_id').innerHTML = '<option value="">Select Player</option>';
        document.getElementById('team2_player2_id').innerHTML = '<option value="">Select Player</option>';
    }

    function updatePlayerDropdowns(players, categoryId) {
        let maleDropdowns = [document.getElementById('team1_player1_id'), document.getElementById('team2_player1_id')];
        let femaleDropdowns = [document.getElementById('team1_player2_id
