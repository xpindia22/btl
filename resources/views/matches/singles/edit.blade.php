@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Singles Matches (Edit Mode)</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

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

    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Tournament</th>
                <th>Category</th>
                <th>Player 1</th>
                <th>Player 2</th>
                <th>Stage</th>
                <th>Set 1 (P1 - P2)</th>
                <th>Set 2 (P1 - P2)</th>
                <th>Set 3 (P1 - P2)</th>
                <th>Date</th>
                <th>Time</th>
                <th>Winner</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($matches as $index => $match)
                <tr>
                    <form method="POST" action="{{ route('matches.singles.update', ['match' => $match->id]) }}">
                        @csrf
                        @method('PUT')
                        <td>{{ $index + 1 }}</td>
                        <td>{{ optional($match->tournament)->name ?? 'N/A' }}</td>
                        <td>{{ optional($match->category)->name ?? 'N/A' }}</td>
                        <td>{{ optional($match->player1)->name ?? 'N/A' }}</td>
                        <td>{{ optional($match->player2)->name ?? 'N/A' }}</td>

                        <td>
                            <select name="stage" class="form-control">
                                @foreach(['Pre Quarter Finals','Quarter Finals','Semifinals','Finals'] as $stage)
                                    <option value="{{ $stage }}" {{ $match->stage == $stage ? 'selected' : '' }}>{{ $stage }}</option>
                                @endforeach
                            </select>
                        </td>

                        <td>
                            <input type="number" name="set1_player1_points" class="form-control w-auto" value="{{ $match->set1_player1_points ?? 0 }}">
                            -
                            <input type="number" name="set1_player2_points" class="form-control w-auto" value="{{ $match->set1_player2_points ?? 0 }}">
                        </td>
                        <td>
                            <input type="number" name="set2_player1_points" class="form-control w-auto" value="{{ $match->set2_player1_points ?? 0 }}">
                            -
                            <input type="number" name="set2_player2_points" class="form-control w-auto" value="{{ $match->set2_player2_points ?? 0 }}">
                        </td>
                        <td>
                            <input type="number" name="set3_player1_points" class="form-control w-auto" value="{{ $match->set3_player1_points ?? 0 }}">
                            -
                            <input type="number" name="set3_player2_points" class="form-control w-auto" value="{{ $match->set3_player2_points ?? 0 }}">
                        </td>

                        <td>
                            <input type="date" name="match_date" class="form-control w-auto" value="{{ $match->match_date ?? '' }}">
                        </td>
                        <td>
                            <input type="time" name="match_time" class="form-control w-auto" value="{{ $match->match_time ?? '' }}">
                        </td>

                        <td>
                            @php
                                $winner = 'TBD';
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

                                if ($p1_sets > $p2_sets) $winner = optional($match->player1)->name;
                                elseif ($p2_sets > $p1_sets) $winner = optional($match->player2)->name;
                                elseif ($p1_sets == $p2_sets) $winner = 'Draw';
                            @endphp
                            {{ $winner }}
                        </td>

                        <td>
                            <button type="submit" class="btn btn-sm btn-success">Save</button>
                        </td>
                    </form>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
