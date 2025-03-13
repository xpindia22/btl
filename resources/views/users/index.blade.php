@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Registered Users</h2>

    @if ($users->isNotEmpty())
        <p>
            <a href="{{ route('users.edit', ['user' => $users->first()->id]) }}" class="btn btn-primary">
                Edit Users
            </a>
        </p>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Mobile No</th>
                <th>Role</th>
                <th>Moderated Tournaments</th> <!-- ✅ Updated -->
                <th>Created Tournaments</th> <!-- ✅ Updated -->
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->mobile_no ?? 'N/A' }}</td>
                    <td>{{ ucfirst($user->role) }}</td>

                    <!-- ✅ Display Moderated Tournaments -->
                    <td>
                        @if($user->moderatedTournaments->isNotEmpty())
                            <ul>
                                @foreach ($user->moderatedTournaments as $tournament)
                                    <li>{{ $tournament->name }} ({{ $tournament->year }})</li>
                                @endforeach
                            </ul>
                        @else
                            <span class="text-muted">None</span>
                        @endif
                    </td>

                    <!-- ✅ Display Created Tournaments -->
                    <td>
                        @if($user->createdTournaments->isNotEmpty())
                            <ul>
                                @foreach ($user->createdTournaments as $tournament)
                                    <li>{{ $tournament->name }} ({{ $tournament->year }})</li>
                                @endforeach
                            </ul>
                        @else
                            <span class="text-danger">N/A (admin)</span> <!-- ✅ Default if admin created -->
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-danger">No users found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- ✅ Pagination for Users -->
    <div class="d-flex justify-content-center">
        {{ $users->appends(request()->query())->links() }}
    </div>

    <!-- ✅ Fix Undefined Variable `$matches` -->
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

        <!-- ✅ Pagination for Matches -->
        <div class="d-flex justify-content-center">
            {{ $matches->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection
