@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Insert Player</h1>

    @if(session('message'))
        <p class="message">{{ session('message') }}</p>
    @endif

    <form method="POST" action="{{ route('players.store') }}" class="form-styled">
        @csrf
        <div class="form-group">
            <label for="name">Player Name:</label>
            <input type="text" name="name" id="name" required>
        </div>

        <div class="form-group">
            <label for="dob">Date of Birth:</label>
            <input type="date" name="dob" id="dob" onchange="calculateAge()" required>
        </div>

        <div class="form-group">
            <label for="age">Age:</label>
            <input type="number" name="age" id="age" readonly required>
        </div>

        <div class="form-group">
            <label for="sex">Sex:</label>
            <select name="sex" id="sex" required>
                <option value="M">Male</option>
                <option value="F">Female</option>
            </select>
        </div>

        <div class="form-group">
            <label for="uid">Unique ID:</label>
            <input type="text" name="uid" id="uid" placeholder="Leave empty to auto-generate">
        </div>

        <button type="submit" class="btn btn-primary">Add Player</button>
    </form>

    <h2>All Players</h2>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Date of Birth</th>
                <th>Age</th>
                <th>Sex</th>
                <th>UID</th>
                <th>Linked Users</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($players as $player)
            <tr>
                <td>{{ $player->id }}</td>
                <td>{{ $player->name }}</td>
                <td>{{ $player->dob }}</td>
                <td>{{ $player->age }}</td>
                <td>{{ $player->sex }}</td>
                <td>{{ $player->uid }}</td>
                <td>
                    @if($player->users->isNotEmpty())
                        {{ $player->users->pluck('username')->implode(', ') }}
                    @else
                        No users linked
                    @endif
                </td>
                <td>
                    @if(Auth::user()->is_admin)
                        <a href="{{ route('players.edit', $player->id) }}" class="btn btn-secondary">Edit</a>
                        <form method="POST" action="{{ route('players.destroy', $player->id) }}" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Are you sure you want to delete this player?')">Delete</button>
                        </form>
                    @else
                        <span class="disabled-action">No Action</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    function calculateAge() {
        const dob = document.getElementById('dob').value;
        const ageField = document.getElementById('age');
        if (dob) {
            const birthDate = new Date(dob);
            const today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            const m = today.getMonth() - birthDate.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            ageField.value = age;
        }
    }
</script>
@endsection
