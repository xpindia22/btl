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
                <th>Moderator</th> <!-- ✅ Updated -->
                <th>Creator</th> <!-- ✅ Updated -->
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

                    <td>
                        @foreach ($user->moderatedTournaments as $tournament)
                            <li>{{ $tournament->name }} ({{ $tournament->year }})</li>
                        @endforeach
                    </td>

                    <td>
                        @foreach ($user->createdTournaments as $tournament)
                            <li>{{ $tournament->name }} ({{ $tournament->year }})</li>
                        @endforeach
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
