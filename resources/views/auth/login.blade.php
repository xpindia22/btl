@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh; background-color: #f8f9fa;">
    <div class="login-box p-4 shadow-lg rounded">
        <h2 class="text-center mb-4">Login</h2>
        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3 d-flex align-items-center">
                <label class="form-label me-2 mb-0" style="width: 80px;">Email:</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3 d-flex align-items-center">
                <label class="form-label me-2 mb-0" style="width: 80px;">Password:</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
    </div>
</div>

<style>
    .login-box {
        width: 400px;
        background:rgb(253, 253, 253);
        border-radius: 10px;
        
    }
</style>
@endsection
