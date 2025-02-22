@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Registered Users</h2>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Mobile No</th>
                <th>Role</th>
                <th>Moderator Of</th> <!-- ✅ Added Moderator Column -->
                <th>Creator Of</th> <!-- ✅ Added Creator Column -->
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->mobile_no }}</td>
                    <td>{{ ucfirst($user->role) }}</td>
                    
                    <!-- ✅ Display Tournaments where User is a Moderator -->
                    <td>
                        @if ($user->moderatedTournaments->isNotEmpty())
                            <ul>
                                @foreach ($user->moderatedTournaments as $tournament)
                                    <li>{{ $tournament->name }} ({{ $tournament->year }})</li>
                                @endforeach
                            </ul>
                        @else
                            <span class="text-muted">Not a moderator</span>
                        @endif
                    </td>

                    <!-- ✅ Display Tournaments Created by User -->
                    <td>
                        @if ($user->createdTournaments->isNotEmpty())
                            <ul>
                                @foreach ($user->createdTournaments as $tournament)
                                    <li>{{ $tournament->name }} ({{ $tournament->year }})</li>
                                @endforeach
                            </ul>
                        @else
                            <span class="text-muted">No tournaments created</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- ✅ Centered Pagination -->
    <div class="d-flex justify-content-center">
        {{ $users->appends(request()->query())->links('vendor.pagination.default') }}
    </div>
</div>
@endsection
