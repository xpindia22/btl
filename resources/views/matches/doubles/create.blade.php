@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Insert Doubles Match</h1>

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

    {{-- Tournament Lock/Unlock Form --}}
    <form method="POST" action="{{ route('matches.doubles.lockTournament') }}">
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
            <button type="submit" formaction="{{ route('matches.doubles.unlockTournament') }}" class="btn btn-danger">Unlock Tournament</button>
        @else
            <button type="submit" class="btn btn-primary">Lock Tournament</button>
        @endif
    </form>

    @if(isset($lockedTournament))
    {{-- Create Doubles Match Form --}}
    <form method="POST" action="{{ route('matches.doubles.store') }}" id="add-match-form">
        @csrf
        <input type="hidden" name="tournament_id" value="{{ $lockedTournament->id }}">

        <!-- Category Dropdown (each option gets a data-type attribute) -->
        <label for="category_id">Category:</label>
        <select name="category_id" id="category_id" required>
            <option value="">Select Category</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}"
                    data-type="{{ (stripos($cat->name, 'XD') !== false) ? 'XD' : ((stripos($cat->name, 'BD') !== false) ? 'BD' : ((stripos($cat->name, 'GD') !== false) ? 'GD' : '')) }}">
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>

        <!-- BD/GD Section (for non-XD categories) -->
        <div id="non_xd_players" style="display:none; margin-top:15px;">
            <label for="player1_id">Team 1 - Player 1:</label>
            <select name="team1Player1" id="player1_id" required disabled>
                <option value="">Select Player</option>
            </select>

            <label for="player2_id">Team 1 - Player 2:</label>
            <select name="team1Player2" id="player2_id" required disabled>
                <option value="">Select Player</option>
            </select>

            <label for="player3_id">Team 2 - Player 1:</label>
            <select name="team2Player1" id="player3_id" required disabled>
                <option value="">Select Player</option>
            </select>

            <label for="player4_id">Team 2 - Player 2:</label>
            <select name="team2Player2" id="player4_id" required disabled>
                <option value="">Select Player</option>
            </select>
        </div>

        <!-- XD Section (for mixed doubles) -->
        <div id="xd_players" style="display:none; margin-top:15px;">
            <label for="team1_boy">Team 1 - Boy:</label>
            <select name="team1Boy" id="team1_boy" required disabled>
                <option value="">Select Boy</option>
            </select>

            <label for="team1_girl">Team 1 - Girl:</label>
            <select name="team1Girl" id="team1_girl" required disabled>
                <option value="">Select Girl</option>
            </select>

            <label for="team2_boy">Team 2 - Boy:</label>
            <select name="team2Boy" id="team2_boy" required disabled>
                <option value="">Select Boy</option>
            </select>

            <label for="team2_girl">Team 2 - Girl:</label>
            <select name="team2Girl" id="team2_girl" required disabled>
                <option value="">Select Girl</option>
            </select>
        </div>

        <label for="stage">Stage:</label>
        <select name="stage" id="stage" required>
            <option value="Pre Quarter Finals">Pre Quarter Finals</option>
            <option value="Quarter Finals">Quarter Finals</option>
            <option value="Semifinals">Semifinals</option>
            <option value="Finals">Finals</option>
        </select>

        <label for="match_date">Match Date:</label>
        <input type="date" name="match_date" id="match_date" required>

        <label for="match_time">Match Time:</label>
        <input type="time" name="match_time" id="match_time" required>

        <!-- Set Scores for Team 1 -->
        <label for="set1_team1_points">Set 1 - Team 1 Points:</label>
        <input type="number" name="set1_team1_points" id="set1_team1_points" value="0" min="0" required>

        <label for="set2_team1_points">Set 2 - Team 1 Points:</label>
        <input type="number" name="set2_team1_points" id="set2_team1_points" value="0" min="0" required>

        <label for="set3_team1_points">Set 3 - Team 1 Points (Optional):</label>
        <input type="number" name="set3_team1_points" id="set3_team1_points" value="0" min="0">

        <!-- Set Scores for Team 2 -->
        <label for="set1_team2_points">Set 1 - Team 2 Points:</label>
        <input type="number" name="set1_team2_points" id="set1_team2_points" value="0" min="0" required>

        <label for="set2_team2_points">Set 2 - Team 2 Points:</label>
        <input type="number" name="set2_team2_points" id="set2_team2_points" value="0" min="0" required>

        <label for="set3_team2_points">Set 3 - Team 2 Points (Optional):</label>
        <input type="number" name="set3_team2_points" id="set3_team2_points" value="0" min="0">

        <button type="submit" class="btn btn-success">Add Match</button>
    </form>
    @endif
</div>

<!-- Include jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
$(document).ready(function() {
    // When category is changed, disable both groups then enable the appropriate one
    $('#category_id').change(function() {
        var categoryId = $(this).val();
        // Force data-type to uppercase for comparison
        var categoryType = ($('#category_id option:selected').data('type') || '').toString().toUpperCase();
        console.log("Selected category:", categoryId, "Type:", categoryType);

        // Hide both sections and disable both groups
        $('#non_xd_players').hide();
        $('#xd_players').hide();
        $('#player1_id, #player2_id, #player3_id, #player4_id').prop('disabled', true);
        $('#team1_boy, #team1_girl, #team2_boy, #team2_girl').prop('disabled', true);

        if (categoryId) {
            $.ajax({
                url: "{{ route('matches.doubles.filteredPlayers') }}",
                type: "GET",
                dataType: 'json',
                data: { category_id: categoryId },
                success: function(players) {
                    console.log("Players received:", players);
                    if (!Array.isArray(players)) {
                        console.error("Response is not an array:", players);
                        return;
                    }
                    if (categoryType === 'XD') {
                        console.log("Processing XD category.");
                        let malePlayers = players.filter(function(player) {
                            let sex = player.sex ? player.sex.toString().toUpperCase() : '';
                            return sex === 'M' || sex === 'MALE';
                        });
                        let femalePlayers = players.filter(function(player) {
                            let sex = player.sex ? player.sex.toString().toUpperCase() : '';
                            return sex === 'F' || sex === 'FEMALE';
                        });
                        console.log("Male players:", malePlayers);
                        console.log("Female players:", femalePlayers);

                        $('#team1_boy').empty().append('<option value="">Select Boy</option>');
                        malePlayers.forEach(function(player) {
                            $('#team1_boy').append(`<option value="${player.id}">${player.name} (Age: ${player.age})</option>`);
                        });
                        $('#team2_boy').empty().append('<option value="">Select Boy</option>');
                        malePlayers.forEach(function(player) {
                            $('#team2_boy').append(`<option value="${player.id}">${player.name} (Age: ${player.age})</option>`);
                        });
                        $('#team1_girl').empty().append('<option value="">Select Girl</option>');
                        femalePlayers.forEach(function(player) {
                            $('#team1_girl').append(`<option value="${player.id}">${player.name} (Age: ${player.age})</option>`);
                        });
                        $('#team2_girl').empty().append('<option value="">Select Girl</option>');
                        femalePlayers.forEach(function(player) {
                            $('#team2_girl').append(`<option value="${player.id}">${player.name} (Age: ${player.age})</option>`);
                        });
                        $('#team1_boy, #team1_girl, #team2_boy, #team2_girl').prop('disabled', false);
                        $('#xd_players').show();
                    } else {
                        $('#player1_id, #player2_id, #player3_id, #player4_id').empty().append('<option value="">Select Player</option>');
                        players.forEach(function(player) {
                            let optionHtml = `<option value="${player.id}">${player.name} (Age: ${player.age}, Sex: ${player.sex})</option>`;
                            $('#player1_id, #player2_id, #player3_id, #player4_id').append(optionHtml);
                        });
                        $('#player1_id, #player2_id, #player3_id, #player4_id').prop('disabled', false);
                        $('#non_xd_players').show();
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", error);
                    console.error("Response:", xhr.responseText);
                    alert("Error fetching players. Please try again.");
                }
            });
        }
    });

    $('#add-match-form').on('submit', function(e) {
        console.log("Add Match form submitted.");
    });
});
</script>
@endsection
