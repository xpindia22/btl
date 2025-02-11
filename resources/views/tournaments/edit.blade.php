@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Tournament</h1>

    {{-- Display Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                   <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('tournaments.update', $tournament->id) }}">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="name">Tournament Name:</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $tournament->name }}" required>
        </div>

        <div class="form-group">
            <label for="categories">Categories:</label>
            <select name="categories[]" id="categories" class="form-control" multiple>
                @foreach($allCategories as $category)
                    <option value="{{ $category->id }}" {{ in_array($category->id, $assignedCategories) ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="moderators">Moderators:</label>
            <select name="moderators[]" id="moderators" class="form-control" multiple>
                @foreach($allModerators as $moderator)
                    <option value="{{ $moderator->id }}" {{ in_array($moderator->id, $assignedModerators) ? 'selected' : '' }}>
                        {{ $moderator->username }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update Tournament</button>
    </form>
</div>
@endsection
