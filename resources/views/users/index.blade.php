@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Registered Users --- 
        @if($users->isNotEmpty())
            <a href="{{ route('users.edit', ['user' => $users->first()->id]) }}">Edit Users</a>
        @endif
    </h2>

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
            @forelse ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->mobile_no ?? 'N/A' }}</td>
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
            @empty
                <tr>
                    <td colspan="7" class="text-center text-danger">No users found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $users->appends(request()->query())->links('vendor.pagination.semantic-ui') }}
    </div>

    <!-- ✅ Fixes Undefined Variable `$matches` -->
    @if(isset($matches) && $matches->isNotEmpty())
        <h3 class="mt-4">Matches</h3>
        <div class="d-flex justify-content-center">
            {{ $matches->appends(request()->query())->links('vendor.pagination.semantic-ui') }}
        </div>
    @endif
</div>
@endsection
