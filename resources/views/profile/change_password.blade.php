@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Change Password</h2>

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

    <form action="{{ route('profile.change_password') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Current Password</label>
            <input type="password" name="current_password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>New Password</label>
            <input type="password" name="new_password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Confirm New Password</label>
            <input type="password" name="new_password_confirmation" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Change Password</button>
    </form>
</div>
@endsection
