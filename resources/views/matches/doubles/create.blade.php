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
        <select name="tournament_id" id="tournament_id" {{ $lockedTournament ? 'disabled' : '' }} required>
            <option value="">Select Championship</option>
            @foreach($championships as $champ)
                <option value="{{ $champ->id }}" {{ $lockedTournament && $lockedTournament->id == $champ->id ? 'selected' : '' }}>
                    {{ $champ->name }}
                </option>
            @endforeach
        </select>

        @if($lockedTournament)
            <button type="submit" formaction="{{ route('matches.doubles.unlockTournament') }}" class="btn btn-danger">Unlock Championship</button>
        @else
            <button type="submit" class="btn btn-primary">Lock Championship</button>
        @endif
    </form>

    @if($lockedTournament)
    <form id="doublesForm" method="POST" action="{{ route('matches.doubles.store') }}">
        @csrf

        <input type="hidden" name="tournament_id" value="{{ $lockedTournament->id }}">

        <!-- Category Dropdown -->
        <label for="category_id">Category:</label>
        <select name="category_id" id="category_id" required>
           <option value="">Select Category</option>
           @foreach($categories as $cat)
             <option value="{{ $cat->id }}">{{ $cat->name }}</option>
           @endforeach
        </select>

        <!-- Sections for Doubles Players -->
        <!-- Mixed Doubles Section (for XD) -->
        <div id="mixed-doubles" style="display: none;">
            <fieldset>
                <legend>Team 1</legend>
                <label for="team1_male">Male Player:</label>
                <select id="team1_male" name="team1_male" required>
                    <option value="">Select Male Player</option>
                </select>
                <label for="team1_female">Female Player:</label>
                <select id="team1_female" name="team1_female" required>
                    <option value="">Select Female Player</option>
                </select>
            </fieldset>
            <fieldset>
                <legend>Team 2</legend>
                <label for="team2_male">Male Player:</label>
                <select id="team2_male" name="team2_male" required>
                    <option value="">Select Male Player</option>
                </select>
                <label for="team2_female">Female Player:</label>
                <select id="team2_female" name="team2_female" required>
                    <option value="">Select Female Player</option>
                </select>
            </fieldset>
        </div>

        <!-- Non-Mixed Doubles Section (for BD or GD) -->
        <div id="non-mixed-doubles" style="display: none;">
            <fieldset>
                <legend>Team 1</legend>
                <label for="team1_player1">Player 1:</label>
                <select id="team1_player1" name="team1_player1" required>
                    <option value="">Select Player</option>
                </select>
                <label for="team1_player2">Player 2:</label>
                <select id="team1_player2" name="team1_player2" required>
                    <option value="">Select Player</option>
                </select>
            </fieldset>
            <fieldset>
                <legend>Team 2</legend>
                <label for="team2_player1">Player 1:</label>
                <select id="team2_player1" name="team2_player1" required>
                    <option value="">Select Player</option>
                </select>
                <label for="team2_player2">Player 2:</label>
                <select id="team2_player2" name="team2_player2" required>
                    <option value="">Select Player</option>
                </select>
            </fieldset>
        </div>

        <!-- Match Details -->
        <label for="stage">Stage:</label>
        <select name="stage" id="stage" required>
            <option value="Pre Quarter Finals">Pre Quarter Finals</option>
            <option value="Quarter Finals">Quarter Finals</option>
            <option value="Semifinals">Semifinals</option>
            <option value="Finals">Finals</option>
        </select>

        <label for="date">Match Date:</label>
<input type="date" name="date" id="date" required>

<label for="match_time">Match Time:</label>
<input type="time" name="match_time" id="match_time" required>

<script>
  window.onload = function() {
    const now = new Date();
    // Set today's date
    document.getElementById('date').valueAsDate = now;
    // Format current time as HH:MM
    document.getElementById('match_time').value = now
      .toTimeString()
      .slice(0, 5);
  };
</script>

        <!-- Set Scores for Doubles Matches -->
        <h3>Set Scores</h3>
        <fieldset>
            <legend>Set 1</legend>
            <label for="set1_team1_points">Team 1 Points:</label>
            <input type="number" name="set1_team1_points" id="set1_team1_points" value="0" required>
            <label for="set1_team2_points">Team 2 Points:</label>
            <input type="number" name="set1_team2_points" id="set1_team2_points" value="0"  required>
        </fieldset>

        <fieldset>
            <legend>Set 2</legend>
            <label for="set2_team1_points">Team 1 Points:</label>
            <input type="number" name="set2_team1_points" id="set2_team1_points" value="0"  required>
            <label for="set2_team2_points">Team 2 Points:</label>
            <input type="number" name="set2_team2_points" id="set2_team2_points" value="0"  required>
        </fieldset>

        <fieldset>
            <legend>Set 3 (Optional)</legend>
            <label for="set3_team1_points">Team 1 Points:</label>
            <input type="number" name="set3_team1_points" id="set3_team1_points" value="0" >
            <label for="set3_team2_points">Team 2 Points:</label>
            <input type="number" name="set3_team2_points" id="set3_team2_points" value="0" >
        </fieldset>

        <button type="submit" class="btn btn-primary">Create Doubles Match</button>
    </form>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('category_id');
    categorySelect.addEventListener('change', function() {
        const categoryId = this.value;
        if (!categoryId) {
            document.getElementById('mixed-doubles').style.display = 'none';
            document.getElementById('non-mixed-doubles').style.display = 'none';
            return;
        }

        // Determine category type by its option text.
        const selectedOption = this.options[this.selectedIndex];
        const catText = selectedOption.text.toUpperCase();
        // Assume that if the text contains 'XD' or 'MIXED', it's a mixed doubles category.
        const isMixed = catText.includes('XD') || catText.includes('MIXED');

        if (isMixed) {
            document.getElementById('mixed-doubles').style.display = 'block';
            document.getElementById('non-mixed-doubles').style.display = 'none';
        } else {
            document.getElementById('mixed-doubles').style.display = 'none';
            document.getElementById('non-mixed-doubles').style.display = 'block';
        }

        // Fetch players via AJAX for the selected category.
        fetch("{{ route('matches.doubles.filteredPlayers') }}?category_id=" + categoryId)
            .then(response => response.json())
            .then(players => {
                console.log("Received players:", players);
                function populateDropdown(element, playersList) {
                    element.innerHTML = '<option value="">Select Player</option>';
                    playersList.forEach(player => {
                        const option = document.createElement('option');
                        option.value = player.id;
                        option.textContent = `${player.name} (Age: ${player.age}, Sex: ${player.sex})`;
                        element.appendChild(option);
                    });
                }

                if (isMixed) {
                    // For mixed doubles, split players by sex.
                    const malePlayers = players.filter(p => p.sex === 'M');
                    const femalePlayers = players.filter(p => p.sex === 'F');
                    
                    populateDropdown(document.getElementById('team1_male'), malePlayers);
                    populateDropdown(document.getElementById('team1_female'), femalePlayers);
                    populateDropdown(document.getElementById('team2_male'), malePlayers);
                    populateDropdown(document.getElementById('team2_female'), femalePlayers);
                } else {
                    // For BD or GD, the players list is already filtered by sex.
                    populateDropdown(document.getElementById('team1_player1'), players);
                    populateDropdown(document.getElementById('team1_player2'), players);
                    populateDropdown(document.getElementById('team2_player1'), players);
                    populateDropdown(document.getElementById('team2_player2'), players);
                }
            })
            .catch(error => console.error("Error fetching players:", error));
    });
});
</script>

<script>
document.getElementById('doublesForm').addEventListener('submit', function(e) {
    console.log("Form submitted!");
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('category_id');

    categorySelect.addEventListener('change', function() {
        const categoryId = this.value;

        if (!categoryId) {
            document.getElementById('mixed-doubles').style.display = 'none';
            document.getElementById('non-mixed-doubles').style.display = 'none';
            removeRequiredFields();
            return;
        }

        // Determine category type by name
        const selectedOption = this.options[this.selectedIndex];
        const catText = selectedOption.text.toUpperCase();
        const isMixed = catText.includes('XD') || catText.includes('MIXED');

        if (isMixed) {
            document.getElementById('mixed-doubles').style.display = 'block';
            document.getElementById('non-mixed-doubles').style.display = 'none';
            setRequiredFields('mixed');
        } else {
            document.getElementById('mixed-doubles').style.display = 'none';
            document.getElementById('non-mixed-doubles').style.display = 'block';
            setRequiredFields('non-mixed');
        }
    });

    function setRequiredFields(type) {
        removeRequiredFields();
        if (type === 'mixed') {
            document.getElementById('team1_male').setAttribute('required', 'required');
            document.getElementById('team1_female').setAttribute('required', 'required');
            document.getElementById('team2_male').setAttribute('required', 'required');
            document.getElementById('team2_female').setAttribute('required', 'required');
        } else {
            document.getElementById('team1_player1').setAttribute('required', 'required');
            document.getElementById('team1_player2').setAttribute('required', 'required');
            document.getElementById('team2_player1').setAttribute('required', 'required');
            document.getElementById('team2_player2').setAttribute('required', 'required');
        }
    }

    function removeRequiredFields() {
        const allFields = ['team1_male', 'team1_female', 'team2_male', 'team2_female', 
                           'team1_player1', 'team1_player2', 'team2_player1', 'team2_player2'];

        allFields.forEach(id => {
            let element = document.getElementById(id);
            if (element) element.removeAttribute('required');
        });
    }

    // Ensure hidden fields do not block form submission
    document.getElementById('doublesForm').addEventListener('submit', function(event) {
        removeRequiredFields(); // Remove required attributes before submission
    });
});
</script>

@endsection
