{{-- resources/views/players/register.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Player Registration</h1>

    @if(session('message'))
        <p class="message">{{ session('message') }}</p>
    @endif

    @if(session('success'))
        <p class="message">{{ session('success') }}</p>
    @endif

    <form method="POST" action="{{ route('player.register') }}">
        @csrf
        <div class="form-group">
            <label for="uid">UID (Editable Auto-Suggested):</label>
            <input type="number" name="uid" id="uid" value="{{ old('uid', $nextUid) }}" required>
        </div>
        <div class="form-group">
            <label for="name">Full Name:</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required>
        </div>
        <div class="form-group">
            <label for="dob">Date of Birth:</label>
            <input type="date" name="dob" id="dob" value="{{ old('dob') }}" required>
        </div>
        <div class="form-group">
            <label for="sex">Gender:</label>
            <select name="sex" id="sex" required>
                <option value="M" {{ old('sex') == 'M' ? 'selected' : '' }}>Male</option>
                <option value="F" {{ old('sex') == 'F' ? 'selected' : '' }}>Female</option>
            </select>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Register</button>
    </form>

    <h2>Registered Players</h2>
    <table border="1">
        <thead>
            <tr>
                <th>#</th>
                <th>UID</th>
                <th>Name</th>
                <th>Date of Birth</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Registered At</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($players as $player)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $player->uid }}</td>
                    <td>{{ $player->name }}</td>
                    <td>{{ $player->dob }}</td>
                    <td>{{ $player->age }}</td>
                    <td>{{ $player->sex }}</td>
                    <td>{{ date("d-m-Y h:i A", strtotime($player->created_at)) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
