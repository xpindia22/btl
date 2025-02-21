@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Create Tournament</h3>
                </div>

                <div class="card-body">
                    {{-- Display Validation Errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Tournament Creation Form --}}
                    <form method="POST" action="{{ route('tournaments.add') }}">
                        @csrf

                        <div class="form-group">
                            <label for="tournament_name" class="font-weight-bold">Tournament Name:</label>
                            <input type="text" name="tournament_name" id="tournament_name" 
                                   class="form-control @error('tournament_name') is-invalid @enderror"
                                   value="{{ old('tournament_name') }}" required>
                            
                            @error('tournament_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-plus-circle"></i> Create Tournament
                            </button>
                            <a href="{{ route('tournaments.manage') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Tournaments
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
