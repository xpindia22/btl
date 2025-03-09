@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Create Tournament</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('tournaments.store') }}">
        @csrf
        <div class="form-group">
            <label for="tournament_name">Tournament Name:</label>
            <input type="text" name="tournament_name" id="tournament_name" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="categories">Select Categories:</label>
            <select name="categories[]" id="categories" class="form-control" multiple>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            <small>Hold Ctrl (Windows) or Cmd (Mac) to select multiple categories.</small>
        </div>

        <div class="form-group">
            <label for="moderators">Select Moderators:</label>
            <select name="moderators[]" id="moderators" class="form-control" multiple>
                @foreach ($moderators as $moderator)
                    <option value="{{ $moderator->id }}">{{ $moderator->username }}</option>
                @endforeach
            </select>
            <small>Hold Ctrl (Windows) or Cmd (Mac) to select multiple moderators.</small>
        </div>

        <button type="submit" class="btn btn-success">Create</button>
        <a href="{{ route('tournaments.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
