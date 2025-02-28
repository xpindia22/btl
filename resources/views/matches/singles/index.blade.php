@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Singles Matches (View-Only)</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Tournament</th>
                <th>Category</th>
                <th>Player 1</th>
                <th>Player 2</th>
                <th>Stage</th>
                <th>Date</th>
                <th>Time</th>
                <th>Set 1</th>
                <th>Set 2</th>
                <th>Set 3</th>
                <th>Winner</th>
            </tr>
        </thead>
        <tbody>
            @foreach($matches as $key => $match)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ optional($match->tournament)->name ?? 'N/A' }}</td>
                    <td>{{ optional($match->category)->name ?? 'N/A' }}</td>
                    <td>{{ optional($match->player1)->name ?? 'N/A' }}</td>
                    <td>{{ optional($match->player2)->name ?? 'N/A' }}</td>
                    <td>{{ $match->stage ?? 'N/A' }}</td>
                    <td>{{ $match->match_date ?? 'N/A' }}</td>
                    <td>{{ $match->match_time ?? 'N/A' }}</td>
                    <td>{{ $match->set1_player1_points ?? 0 }} - {{ $match->set1_player2_points ?? 0 }}</td>
                    <td>{{ $match->set2_player1_points ?? 0 }} - {{ $match->set2_player2_points ?? 0 }}</td>
                    <td>
                        @if(!is_null($match->set3_player1_points) && !is_null($match->set3_player2_points))
                            {{ $match->set3_player1_points }} - {{ $match->set3_player2_points }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        @php
                            $p1_sets = 0; 
                            $p2_sets = 0;

                            if ($match->set1_player1_points > $match->set1_player2_points) $p1_sets++;
                            if ($match->set1_player2_points > $match->set1_player1_points) $p2_sets++;

                            if ($match->set2_player1_points > $match->set2_player2_points) $p1_sets++;
                            if ($match->set2_player2_points > $match->set2_player1_points) $p2_sets++;

                            if (!is_null($match->set3_player1_points) && !is_null($match->set3_player2_points)) {
                                if ($match->set3_player1_points > $match->set3_player2_points) $p1_sets++;
                                if ($match->set3_player2_points > $match->set3_player1_points) $p2_sets++;
                            }

                            $winner = $p1_sets > $p2_sets ? optional($match->player1)->name : 
                                      ($p2_sets > $p1_sets ? optional($match->player2)->name : 'Draw');
                        @endphp
                        {{ $winner }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<!-- Pagination -->
<div class="d-flex justify-content-center">
    {{ $matches->appends(request()->query())->links('vendor.pagination.default') }}
</div>
@endsection
