@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="text-center">Manage Tournaments</h2>

    {{-- Success/Error Messages --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="table-container">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th style="width: 200px;">Tournament Name</th>
                    <th style="width: 120px;">Created By</th>
                    <th style="width: 180px;">Moderators</th>
                    <th style="width: 220px;">Categories</th> <!-- Fixed width for categories -->
                    <th style="width: 120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tournaments as $tournament)
                    @php
                        $categories = explode(', ', $tournament->categories ?? 'None');
                        $chunks = array_chunk($categories, 2); // Group categories into pairs
                        $maxRows = count($chunks);
                    @endphp

                    @for ($i = 0; $i < $maxRows; $i++)
                        <tr>
                            @if ($i == 0)
                                <td rowspan="{{ $maxRows }}">{{ $tournament->tournament_id }}</td>
                                <td rowspan="{{ $maxRows }}">{{ $tournament->tournament_name }}</td>
                                <td rowspan="{{ $maxRows }}">{{ $tournament->created_by ?? 'Unknown' }}</td>
                                <td rowspan="{{ $maxRows }}">{{ $tournament->moderators ?? 'None' }}</td>
                            @endif

                            <td>
                                {{ $chunks[$i][0] ?? '' }} 
                                @if(isset($chunks[$i][1]))
                                    , {{ $chunks[$i][1] }}
                                @endif
                            </td>

                            @if ($i == 0)
                                <td rowspan="{{ $maxRows }}">
                                    <a href="{{ route('tournaments.edit', $tournament->tournament_id) }}" class="btn btn-sm btn-primary">Edit</a>
                                    <form action="{{ route('tournaments.destroy', $tournament->tournament_id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            @endif
                        </tr>
                    @endfor
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="pagination justify-content-center">
        {{ $tournaments->links() }}
    </div>
</div>
@endsection
