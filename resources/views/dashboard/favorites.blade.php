@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 text-center">My Pinned Favorites</h2>

    {{-- Check if any favorites exist --}}
    @if($favorites->isEmpty())
        <div class="alert alert-info text-center">No items pinned yet.</div>
    @else

        <!-- Matches Table -->
        <h4 class="mt-4">Pinned Matches</h4>
        <table class="table table-bordered">
            <thead class="table-dark">
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
                @foreach ($favorites->where('favoritable_type', 'App\Models\Matches') as $favorite)
                    @php $match = \App\Models\Matches::find($favorite->favoritable_id); @endphp
                    @if($match)
                        <tr>
                            <td>{{ $match->id }}</td>
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
                                @if($match->set3_player1_points !== null && $match->set3_player2_points !== null)
                                    {{ $match->set3_player1_points }} - {{ $match->set3_player2_points }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                @php
                                    $p1_sets = ($match->set1_player1_points > $match->set1_player2_points) + 
                                               ($match->set2_player1_points > $match->set2_player2_points) + 
                                               ($match->set3_player1_points > $match->set3_player2_points);
                                    $p2_sets = ($match->set1_player2_points > $match->set1_player1_points) + 
                                               ($match->set2_player2_points > $match->set2_player1_points) + 
                                               ($match->set3_player2_points > $match->set3_player1_points);
                                    $winner = $p1_sets > $p2_sets ? optional($match->player1)->name : ($p2_sets > $p1_sets ? optional($match->player2)->name : 'Draw');
                                @endphp
                                {{ $winner }}
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>

        <!-- Players Table -->
        <h4 class="mt-4">Pinned Players</h4>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>UID</th>
                    <th>Name</th>
                    <th>Date of Birth</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>Registered At</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($favorites->where('favoritable_type', 'App\Models\Player') as $favorite)
                    @php $player = \App\Models\Player::find($favorite->favoritable_id); @endphp
                    @if($player)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $player->uid }}</td>
                            <td>{{ $player->name }}</td>
                            <td>{{ $player->dob }}</td>
                            <td>{{ $player->age }}</td>
                            <td>{{ $player->sex }}</td>
                            <td>{{ date("d-m-Y h:i A", strtotime($player->created_at)) }}</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>

    @endif
</div>
@endsection
