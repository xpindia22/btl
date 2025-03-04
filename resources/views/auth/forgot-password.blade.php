@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Forgot Password</h2>
<<<<<<< HEAD
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form action="{{ route('password.email') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Email</label>
=======
    
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

    <form action="{{ route('password.email') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Email Address</label>
>>>>>>> 2cb62146d248c9fb1fded5d6b30e24a8e11a9823
            <input type="email" name="email" class="form-control" required>
            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <button type="submit" class="btn btn-primary">Send Reset Link</button>
    </form>
</div>
@endsection
