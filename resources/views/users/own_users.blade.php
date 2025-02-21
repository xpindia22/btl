@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Manage Your Users</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role }}</td>
                    <td>
                        <a href="{{ route('admin.edit_user', $user->id) }}" class="btn btn-primary btn-sm">Edit</a>
                        <button class="btn btn-danger btn-sm delete-user" data-id="{{ $user->id }}">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
