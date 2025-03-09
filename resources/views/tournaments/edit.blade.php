@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="text-center">Edit Tournament</h3>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tournament Name</th>
                <th>Created By</th>
                <th>Categories</th>
                <th>Moderators</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tournaments as $tournament)
                @php
                    // Convert category & moderator lists to arrays for selection in dropdowns
                    $selectedCategories = explode(', ', $tournament->categories ?? '');
                    $selectedModerators = explode(', ', $tournament->moderators ?? '');
                @endphp
                <tr>
                    <form method="POST" action="{{ route('tournaments.update', $tournament->tournament_id) }}">
                        @csrf
                        @method('PUT')

                        <td>{{ $tournament->tournament_id }}</td>
                        <td>
                            <input type="text" name="name" value="{{ $tournament->tournament_name }}" class="form-control" required>
                        </td>
                        <td>{{ $tournament->created_by }}</td>

                        <!-- Categories Dropdown (Multi-Select) -->
                        <td>
                            <select name="categories[]" class="form-control" multiple>
                                @foreach ($allCategories as $category)
                                    <option value="{{ $category->id }}" 
                                        {{ in_array($category->name, $selectedCategories) ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small>Hold Ctrl (Windows) or Cmd (Mac) to select multiple categories.</small>
                        </td>

                        <!-- Moderators Dropdown (Multi-Select) -->
                        <td>
                            <select name="moderators[]" class="form-control" multiple>
                                @foreach ($allModerators as $moderator)
                                    <option value="{{ $moderator->id }}" 
                                        {{ in_array($moderator->username, $selectedModerators) ? 'selected' : '' }}>
                                        {{ $moderator->username }}
                                    </option>
                                @endforeach
                            </select>
                            <small>Hold Ctrl (Windows) or Cmd (Mac) to select multiple moderators.</small>
                        </td>

                        <td>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </td>
                    </form>
                    
                    <!-- Corrected Delete Form -->
                    <td>
                        <form method="POST" action="{{ route('tournaments.destroy', $tournament->tournament_id) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
