@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="text-center">Tournaments List</h3>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('tournaments.create') }}" class="btn btn-success mb-3">Add Tournament</a>
    <a href="{{ route('tournaments.edit') }}" class="btn btn-primary mb-3">Manage Tournaments</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Created By</th>
                <th>Categories</th>
                <th>Moderators</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tournaments as $tournament)
                @php
                    $categories = explode(', ', $tournament->categories ?? 'None');
                    $chunks = array_chunk($categories, 4);
                    $maxRows = count($chunks);
                @endphp

                @for ($i = 0; $i < $maxRows; $i++)
                    <tr>
                        @if ($i == 0)
                            <td rowspan="{{ $maxRows }}">{{ $tournament->tournament_id }}</td>
                            <td rowspan="{{ $maxRows }}">{{ $tournament->tournament_name }}</td>
                            <td rowspan="{{ $maxRows }}">{{ $tournament->created_by }}</td>
                        @endif
                        <td>{{ implode(', ', $chunks[$i]) }}</td>
                        @if ($i == 0)
                            <td rowspan="{{ $maxRows }}">{{ $tournament->moderators ?? 'None' }}</td>
                        @endif
                    </tr>
                @endfor
            @endforeach
        </tbody>
    </table>

    {{ $tournaments->links() }}
</div>
@endsection
