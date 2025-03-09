@extends('layouts.app')

@section('content')
<div class="container" >
    <h2>Registered Users --- <a href="{{ route('users.edit', ['user' => $users->first()->id ?? null]) }}">Edit Users</a></h2>

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
                        @if($user->moderatedTournaments->isNotEmpty())
                            <ul>
                                @foreach ($user->moderatedTournaments as $tournament)
                                    <li>{{ $tournament->name }} ({{ $tournament->year }})</li>
                                @endforeach
                            </ul>
                        @else
                            <span>None</span>
                        @endif
                    </td>

                    <td>
                        @if($user->createdTournaments->isNotEmpty())
                            <ul>
                                @foreach ($user->createdTournaments as $tournament)
                                    <li>{{ $tournament->name }} ({{ $tournament->year }})</li>
                                @endforeach
                            </ul>
                        @else
                            <span class="text-danger">xxx (admin)</span> <!-- ✅ Shows Default Admin -->
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $matches->appends(request()->query())->links('vendor.pagination.semantic-ui') }}
    </div>
</div>
</div>
@endsection
