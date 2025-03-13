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
                        <td><input type="text" name="username" value="{{ old('username', $user->username) }}" class="form-control" required></td>
                        <td><input type="email" name="email" value="{{ old('email', $user->email) }}" required class="form-control"></td>
                        <td><input type="text" name="mobile_no" value="{{ old('mobile_no', $user->mobile_no) }}" class="form-control"></td>

                        <td><input type="date" name="dob" value="{{ old('dob', $user->dob) }}" class="form-control"></td>

                        <td>
                            <select name="sex" class="form-control">
                                <option value="Male" {{ old('sex', $user->sex) == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('sex', $user->sex) == 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Other" {{ old('sex', $user->sex) == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </td>

                        <td>
                            <select name="role" class="form-control">
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                                <option value="visitor" {{ old('role', $user->role) == 'visitor' ? 'selected' : '' }}>Visitor</option>
                                <option value="player" {{ old('role', $user->role) == 'player' ? 'selected' : '' }}>Player</option>
                            </select>
                        </td>

                        <!-- ✅ Moderated Tournaments -->
                        <td>
                            @if($tournaments->isNotEmpty())
                                @foreach ($tournaments as $tournament)
                                    <label>
                                        <input type="checkbox" name="moderated_tournaments[]" value="{{ $tournament->id }}"
                                        {{ $user->moderatedTournaments->contains($tournament->id) ? 'checked' : '' }}>
                                        {{ $tournament->name }} ({{ $tournament->year }})
                                    </label><br>
                                @endforeach
                            @else
                                <span class="text-muted">No tournaments available</span>
                            @endif
                        </td>

                        <!-- ✅ Created Tournaments -->
                        <td>
                            @if($tournaments->isNotEmpty())
                                @foreach ($tournaments as $tournament)
                                    <label>
                                        <input type="checkbox" name="created_tournaments[]" value="{{ $tournament->id }}"
                                            {{ $user->createdTournaments->contains('id', $tournament->id) ? 'checked' : '' }}>
                                        {{ $tournament->name }} ({{ $tournament->year }})
                                    </label><br>
                                @endforeach
                            @endif

                            @if ($user->createdTournaments->isEmpty())
                                <span class="text-danger">N/A (admin)</span>
                            @endif
                        </td>

                        <!-- ✅ Actions -->
                        <td>
                            <button type="submit" class="btn btn-primary btn-sm">Update</button>
                        </td>
                    </form>

                    <td>
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

    <!-- ✅ Pagination -->
    <div class="d-flex justify-content-center">
        {{ $users->appends(request()->query())->links() }}
    </div>

    <!-- ✅ Matches Section -->
    @if(isset($matches) && $matches->isNotEmpty())
        <h3 class="mt-4">Matches</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Match Name</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($matches as $match)
                    <tr>
                        <td>{{ $match->id }}</td>
                        <td>{{ $match->name }}</td>
                        <td>{{ $match->date }}</td>
                        <td>{{ ucfirst($match->status) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-center">
            {{ $matches->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection
