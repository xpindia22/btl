<table class="table table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Tournament</th>
            <th>Category</th>
            <th>Player 1</th>
            <th>Player 2</th>
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
            <td>{{ $match->tournament->name ?? 'N/A' }}</td>
            <td>{{ $match->category->name ?? 'N/A' }}</td>
            <td>{{ $match->player1->name ?? 'N/A' }}</td>
            <td>{{ $match->player2->name ?? 'N/A' }}</td>
            <td>{{ $match->set1_player1_points }} - {{ $match->set1_player2_points }}</td>
            <td>{{ $match->set2_player1_points }} - {{ $match->set2_player2_points }}</td>
            <td>
                @if($match->set3_player1_points !== null && $match->set3_player2_points !== null)
                    {{ $match->set3_player1_points }} - {{ $match->set3_player2_points }}
                @else
                    N/A
                @endif
            </td>
            <td>
                @php
                    // Determine the winner based on set wins
                    $player1_sets_won = 0;
                    $player2_sets_won = 0;

                    if ($match->set1_player1_points > $match->set1_player2_points) $player1_sets_won++;
                    if ($match->set1_player2_points > $match->set1_player1_points) $player2_sets_won++;

                    if ($match->set2_player1_points > $match->set2_player2_points) $player1_sets_won++;
                    if ($match->set2_player2_points > $match->set2_player1_points) $player2_sets_won++;

                    if ($match->set3_player1_points !== null && $match->set3_player2_points !== null) {
                        if ($match->set3_player1_points > $match->set3_player2_points) $player1_sets_won++;
                        if ($match->set3_player2_points > $match->set3_player1_points) $player2_sets_won++;
                    }

                    $winner = ($player1_sets_won > $player2_sets_won) ? ($match->player1->name ?? 'N/A') : ($player2_sets_won > $player1_sets_won ? ($match->player2->name ?? 'N/A') : 'Draw');
                @endphp
                {{ $winner }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- Render pagination links -->
<div class="d-flex justify-content-center">
    {{ $matches->appends(request()->query())->links() }}
</div>
