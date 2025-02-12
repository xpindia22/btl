{{-- resources/views/players/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Player</h1>

    @if(session('message'))
        <div class="alert alert-danger">{{ session('message') }}</div>
    @endif

    <form action="{{ route('players.update', $player->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="uid">UID:</label>
            <input type="number" name="uid" id="uid" value="{{ old('uid', $player->uid) }}" class="form-control" readonly>
        </div>
        
        <div class="form-group">
            <label for="name">Full Name:</label>
            <input type="text" name="name" id="name" value="{{ old('name', $player->name) }}" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="dob">Date of Birth:</label>
            <input type="date" name="dob" id="dob" value="{{ old('dob', $player->dob) }}" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="sex">Gender:</label>
            <select name="sex" id="sex" class="form-control" required>
                <option value="M" {{ $player->sex === 'M' ? 'selected' : '' }}>Male</option>
                <option value="F" {{ $player->sex === 'F' ? 'selected' : '' }}>Female</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="password">Password (leave blank to keep current):</label>
            <input type="password" name="password" id="password" class="form-control">
        </div>
        
        <button type="submit" class="btn btn-primary">Update Player</button>
    </form>
</div>
@endsection
