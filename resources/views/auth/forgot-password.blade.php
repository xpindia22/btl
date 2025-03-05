@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Forgot Password</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Send Reset Link</button>
    </form>

    @if(auth()->user()->isAdmin())
        <h3 class="mt-5">All Password Reset Requests</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Reset Link</th>
                    <th>Created</th>
                    <th>Expires</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach(DB::table('admin_password_resets')->orderBy('created_at', 'desc')->get() as $reset)
                    @php
                        $user = DB::table('users')->where('email', $reset->email)->first();
                    @endphp
                    <tr>
                        <td>{{ $user->username ?? 'Unknown' }}</td> <!-- ✅ Username Column -->
                        <td>{{ $reset->email }}</td>
                        <td>
                            @if (Carbon\Carbon::parse($reset->expires_at)->isFuture())
                                <a href="{{ $reset->reset_link }}" target="_blank">Reset Password</a>
                            @else
                                <span class="text-danger">Expired</span>
                            @endif
                        </td>
                        <td>{{ Carbon\Carbon::parse($reset->created_at)->diffForHumans() }}</td>
                        <td>{{ $reset->expires_at ? Carbon\Carbon::parse($reset->expires_at)->diffForHumans() : 'N/A' }}</td>
                        <td>
                            <form action="{{ route('admin.deletePasswordReset', $reset->email) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this reset link?')">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
