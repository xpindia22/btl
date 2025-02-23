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
    <form id="doublesForm" method="POST" action="{{ route('matches.doubles.store', ['tournament' => $lockedTournament->id]) }}">
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
        <div id="player-selection"></div>

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

        <button type="submit" class="btn btn-primary">Create Doubles Match</button>
    </form>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('category_id');
    const playerSelectionDiv = document.getElementById('player-selection');

    categorySelect.addEventListener('change', function() {
        const categoryId = this.value;
        if (!categoryId) {
            playerSelectionDiv.innerHTML = '';
            return;
        }

        fetch("{{ route('matches.doubles.filteredPlayers', ['category_id' => '']) }}" + categoryId)
            .then(response => response.json())
            .then(players => {
                playerSelectionDiv.innerHTML = renderPlayerSelection(players, categoryId);
            })
            .catch(error => console.error("Error fetching players:", error));
    });

    function renderPlayerSelection(players, categoryId) {
        let html = `<p>Select players for category: ${categoryId}</p>`;
        players.forEach(player => {
            html += `<label><input type="checkbox" name="players[]" value="${player.id}"> ${player.name} (Age: ${player.age}, Sex: ${player.sex})</label><br>`;
        });
        return html;
    }
});
</script>

@endsection
