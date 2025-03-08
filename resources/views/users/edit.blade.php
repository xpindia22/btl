@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Manage Users - <a href="{{ route('users.index') }}">View Users</a></h2>

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

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Mobile No</th>
                <th>DOB</th>
                <th>Sex</th>
                <th>Role</th>
                <th>Moderated Tournaments</th>
                <th>Created Tournaments</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <form method="POST" action="{{ route('users.update', $user->id) }}">
                        @csrf
                        @method('PUT')

                        <td>{{ $user->id }}</td>
                        <td><input type="text" name="username" value="{{ $user->username }}" class="form-control" required></td>
                        <td><input type="email" name="email" value="{{ $user->email }}" required class="form-control"></td>
                        <td><input type="text" name="mobile_no" value="{{ $user->mobile_no }}" class="form-control"></td>

                        <td><input type="date" name="dob" value="{{ $user->dob }}" class="form-control"></td>

                        <td>
                            <select name="sex" class="form-control">
                                <option {{ $user->sex == 'Male' ? 'selected' : '' }}>Male</option>
                                <option {{ $user->sex == 'Female' ? 'selected' : '' }}>Female</option>
                                <option {{ $user->sex == 'Other' ? 'selected' : '' }}>Other</option>
                        </select></td>

                        <td>
                            <select name="role" class="form-control">
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                                <option value="visitor" {{ $user->role == 'visitor' ? 'selected' : '' }}>Visitor</option>
                                <option value="player" {{ $user->role == 'player' ? 'selected' : '' }}>Player</option>
                            </select>
                        </td>

                        <td>
                            @foreach ($tournaments as $tournament)
                                <label>
                                    <input type="checkbox" name="moderated_tournaments[]" value="{{ $tournament->id }}"
                                    {{ $user->moderatedTournaments->contains($tournament->id) ? 'checked' : '' }}>
                                    {{ $tournament->name }} ({{ $tournament->year }})
                                </label><br>
                            @endforeach
                        </td>

                        <td>
                            @foreach ($tournaments as $tournament)
                                <label>
                                    <input type="checkbox" name="created_tournaments[]" value="{{ $tournament->id }}"
                                        {{ $user->createdTournaments->contains('id', $tournament->id) ? 'checked' : '' }}>
                                    {{ $tournament->name }} ({{ $tournament->year }})
                                </label><br>
                            @endforeach

                            @if ($user->createdTournaments->isEmpty())
                                <span class="text-danger">xxx (admin)</span>
                            @endif
                        </td>

                        <td>
                            <button type="submit" class="btn btn-primary btn-sm">Update</button>
                    </form>

                    <form method="POST" action="{{ route('users.destroy', $user->id) }}" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $users->links('vendor.pagination.default') }}
    </div>
</div>
@endsection
