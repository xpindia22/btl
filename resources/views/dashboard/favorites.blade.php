@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 text-center">My Pinned Favorites</h2>

    @if($favorites->isEmpty())
        <div class="alert alert-info text-center">No items pinned yet.</div>
    @else

        <!-- Pinned Tournaments -->
        <h4 class="mt-4">Pinned Tournaments</h4>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Tournament Name</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Categories</th>
                    <th>Total Matches</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($favorites->where('favoritable_type', 'App\Models\Tournament') as $favorite)
                    @php 
                        $tournament = \App\Models\Tournament::find($favorite->favoritable_id); 
                    @endphp
                    @if($tournament)
                        <tr>
                            <td>{{ $tournament->id }}</td>
                            <td>{{ $tournament->name }}</td>
                            <td>{{ $tournament->start_date ?? 'N/A' }}</td>
                            <td>{{ $tournament->end_date ?? 'N/A' }}</td>
                            <td>
                                @foreach ($tournament->categories as $category)
                                    <span class="badge bg-primary">{{ $category->name }}</span>
                                @endforeach
                            </td>
                            <td>{{ $tournament->matches->count() }}</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>

        <!-- Pinned Singles Matches (if category contains 'BS' or 'GS') -->
        <h4 class="mt-4">Pinned Singles Matches</h4>
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
                    @php 
                        $match = \App\Models\Matches::find($favorite->favoritable_id); 
                        $categoryName = $match ? optional($match->category)->name ?? '' : ''; 
                    @endphp
                    @if($match && (stripos($categoryName, 'BS') !== false || stripos($categoryName, 'GS') !== false))
                        <tr>
                            <td>{{ $match->id }}</td>
                            <td>{{ optional($match->tournament)->name ?? 'N/A' }}</td>
                            <td>{{ $categoryName }}</td>
                            <td>{{ optional($match->player1)->name ?? 'N/A' }}</td>
                            <td>{{ optional($match->player2)->name ?? 'N/A' }}</td>
                            <td>{{ $match->stage ?? 'N/A' }}</td>
                            <td>{{ $match->match_date ?? 'N/A' }}</td>
                            <td>{{ $match->match_time ?? 'N/A' }}</td>
                            <td>{{ $match->set1_player1_points ?? '0' }} - {{ $match->set1_player2_points ?? '0' }}</td>
                            <td>{{ $match->set2_player1_points ?? '0' }} - {{ $match->set2_player2_points ?? '0' }}</td>
                            <td>{{ $match->set3_player1_points ?? '0' }} - {{ $match->set3_player2_points ?? '0' }}</td>
                            <td>
                                @php
                                    $winner = ($match->player1_score > $match->player2_score) 
                                        ? optional($match->player1)->name 
                                        : optional($match->player2)->name;
                                @endphp
                                {{ $winner ?? 'N/A' }}
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>

        <!-- Pinned Doubles Matches (if category contains 'GD', 'BD' or 'XD') -->
        <h4 class="mt-4">Pinned Doubles Matches</h4>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Tournament</th>
                    <th>Category</th>
                    <th>Team 1</th>
                    <th>Team 2</th>
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
                    @php 
                        $match = \App\Models\Matches::find($favorite->favoritable_id); 
                        $categoryName = $match ? optional($match->category)->name ?? '' : ''; 
                    @endphp
                    @if($match && (stripos($categoryName, 'GD') !== false || stripos($categoryName, 'BD') !== false || stripos($categoryName, 'XD') !== false))
                        @php
                            // Fetch team players
                            $team1_player1 = optional(\App\Models\Player::find($match->team1_player1_id))->name ?? 'N/A';
                            $team1_player2 = optional(\App\Models\Player::find($match->team1_player2_id))->name ?? 'N/A';
                            $team2_player1 = optional(\App\Models\Player::find($match->team2_player1_id))->name ?? 'N/A';
                            $team2_player2 = optional(\App\Models\Player::find($match->team2_player2_id))->name ?? 'N/A';

                            // Calculate winning team
                            $team1_sets_won = ($match->set1_team1_points > $match->set1_team2_points ? 1 : 0) +
                                              ($match->set2_team1_points > $match->set2_team2_points ? 1 : 0) +
                                              ($match->set3_team1_points > $match->set3_team2_points ? 1 : 0);

                            $team2_sets_won = ($match->set1_team2_points > $match->set1_team1_points ? 1 : 0) +
                                              ($match->set2_team2_points > $match->set2_team1_points ? 1 : 0) +
                                              ($match->set3_team2_points > $match->set3_team1_points ? 1 : 0);

                            $winner = $team1_sets_won > $team2_sets_won 
                                ? "$team1_player1 & $team1_player2" 
                                : "$team2_player1 & $team2_player2";
                        @endphp

                        <tr>
                            <td>{{ $match->id }}</td>
                            <td>{{ optional($match->tournament)->name ?? 'N/A' }}</td>
                            <td>{{ $categoryName }}</td>
                            <td>{{ $team1_player1 }} & {{ $team1_player2 }}</td>
                            <td>{{ $team2_player1 }} & {{ $team2_player2 }}</td>
                            <td>{{ $match->stage ?? 'N/A' }}</td>
                            <td>{{ $match->match_date ?? 'N/A' }}</td>
                            <td>{{ $match->match_time ?? 'N/A' }}</td>
                            <td>{{ $match->set1_team1_points }} - {{ $match->set1_team2_points }}</td>
                            <td>{{ $match->set2_team1_points }} - {{ $match->set2_team2_points }}</td>
                            <td>{{ $match->set3_team1_points }} - {{ $match->set3_team2_points }}</td>
                            <td>{{ $winner ?? 'N/A' }}</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>

        <!-- Pinned Players -->
        <h4 class="mt-4">Pinned Players</h4>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Player Name</th>
                    <th>Age</th>
                    <th>Sex</th>
                    <th>UID</th>
                    <th>Date Joined</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($favorites->where('favoritable_type', 'App\Models\Player') as $favorite)
                    @php 
                        $player = \App\Models\Player::find($favorite->favoritable_id);
                    @endphp
                    @if($player)
                        <tr>
                            <td>{{ $player->id }}</td>
                            <td>{{ $player->name }}</td>
                            <td>{{ $player->age ?? 'N/A' }}</td>
                            <td>{{ $player->sex ?? 'N/A' }}</td>
                            <td>{{ $player->uid ?? 'N/A' }}</td>
                            <td>{{ $player->date_joined ?? $player->created_at ?? 'N/A' }}</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>

    @endif
</div>
@endsection
