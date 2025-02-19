@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Singles Matches</h1>

    {{-- Display success message --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Display error messages --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> Please fix the following errors:
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Table of Matches --}}
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Tournament</th>
                <th>Category</th>
                <th>Player 1</th>
                <th>Player 2</th>
                <th>Set 1 (P1 - P2)</th>
                <th>Set 2 (P1 - P2)</th>
                <th>Set 3 (P1 - P2)</th>
                <th>Winner</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($matches as $key => $match)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $match->tournament->name ?? 'N/A' }}</td>
                <td>{{ $match->category->name ?? 'N/A' }}</td>
                <td>{{ $match->player1->name ?? 'N/A' }}</td>
                <td>{{ $match->player2->name ?? 'N/A' }}</td>

                {{-- ✅ Display Set Scores with Proper NULL Handling --}}
                <td>
                    <input type="number" name="set1_player1_points" value="{{ $match->set1_player1_points ?? 0 }}" class="form-control">
                    -
                    <input type="number" name="set1_player2_points" value="{{ $match->set1_player2_points ?? 0 }}" class="form-control">
                </td>
                <td>
                    <input type="number" name="set2_player1_points" value="{{ $match->set2_player1_points ?? 0 }}" class="form-control">
                    -
                    <input type="number" name="set2_player2_points" value="{{ $match->set2_player2_points ?? 0 }}" class="form-control">
                </td>
                <td>
                    <input type="number" name="set3_player1_points" value="{{ $match->set3_player1_points ?? 0 }}" class="form-control">
                    -
                    <input type="number" name="set3_player2_points" value="{{ $match->set3_player2_points ?? 0 }}" class="form-control">
                </td>

                {{-- ✅ Determine Winner --}}
                <td>
                    @php
                        $player1_sets_won = 0;
                        $player2_sets_won = 0;

                        if ($match->set1_player1_points > $match->set1_player2_points) $player1_sets_won++;
                        if ($match->set1_player2_points > $match->set1_player1_points) $player2_sets_won++;

                        if ($match->set2_player1_points > $match->set2_player2_points) $player1_sets_won++;
                        if ($match->set2_player2_points > $match->set2_player1_points) $player2_sets_won++;

                        if (!is_null($match->set3_player1_points) && !is_null($match->set3_player2_points)) {
                            if ($match->set3_player1_points > $match->set3_player2_points) $player1_sets_won++;
                            if ($match->set3_player2_points > $match->set3_player1_points) $player2_sets_won++;
                        }

                        $winner = $player1_sets_won > $player2_sets_won ? $match->player1->name : ($player2_sets_won > $player1_sets_won ? $match->player2->name : 'Draw');
                    @endphp
                    {{ $winner }}
                </td>

                {{-- ✅ Edit & Delete Options --}}
                <td>
                    <a href="{{ route('matches.singles.edit', $match->id) }}" class="btn btn-primary btn-sm">Edit</a>
                    <form action="{{ route('matches.singles.delete', $match->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Render pagination links -->
    <div class="d-flex justify-content-center">
        {{ $matches->appends(request()->query())->links() }}
    </div>
</div>
@endsection
