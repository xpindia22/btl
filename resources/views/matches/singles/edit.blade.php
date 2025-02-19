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

    {{-- Inline Editable Table --}}
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
                <th>Winner</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($matches as $index => $match)
            <tr>
                {{-- We'll wrap each row in a form to update that row. --}}
                <form method="POST" action="{{ route('matches.singles.updateSingle', $match->id) }}">
                    @csrf
                    @method('PUT')
                    <td>{{ $index + 1 }}</td>
                    <td>{{ optional($match->tournament)->name ?? 'N/A' }}</td>
                    <td>{{ optional($match->category)->name ?? 'N/A' }}</td>
                    <td>{{ optional($match->player1)->name ?? 'N/A' }}</td>
                    <td>{{ optional($match->player2)->name ?? 'N/A' }}</td>

                    {{-- Stage (Editable) --}}
                    <td>
                        <input type="text" name="stage" class="form-control" value="{{ $match->stage }}">
                        {{-- Or a select if you have specific options: 
                        <select name="stage" class="form-control">
                            @foreach(['Pre Quarter Finals','Quarter Finals','Semifinals','Finals'] as $stage)
                                <option value="{{ $stage }}" {{ $match->stage == $stage ? 'selected' : '' }}>{{ $stage }}</option>
                            @endforeach
                        </select>
                        --}}
                    </td>

                    {{-- Set 1 (Editable) --}}
                    <td>
                        <input type="number" name="set1_player1_points" class="form-control d-inline-block" style="width:70px;" value="{{ $match->set1_player1_points ?? 0 }}"> 
                        - 
                        <input type="number" name="set1_player2_points" class="form-control d-inline-block" style="width:70px;" value="{{ $match->set1_player2_points ?? 0 }}">
                    </td>

                    {{-- Set 2 (Editable) --}}
                    <td>
                        <input type="number" name="set2_player1_points" class="form-control d-inline-block" style="width:70px;" value="{{ $match->set2_player1_points ?? 0 }}"> 
                        - 
                        <input type="number" name="set2_player2_points" class="form-control d-inline-block" style="width:70px;" value="{{ $match->set2_player2_points ?? 0 }}">
                    </td>

                    {{-- Set 3 (Editable) --}}
                    <td>
                        <input type="number" name="set3_player1_points" class="form-control d-inline-block" style="width:70px;" value="{{ $match->set3_player1_points ?? 0 }}"> 
                        - 
                        <input type="number" name="set3_player2_points" class="form-control d-inline-block" style="width:70px;" value="{{ $match->set3_player2_points ?? 0 }}">
                    </td>

                    {{-- Winner (Not editable, but displayed) --}}
                    <td>
                        @php
                            $p1_sets = 0; 
                            $p2_sets = 0;
                            if(($match->set1_player1_points ?? 0) > ($match->set1_player2_points ?? 0)) $p1_sets++;
                            if(($match->set1_player2_points ?? 0) > ($match->set1_player1_points ?? 0)) $p2_sets++;

                            if(($match->set2_player1_points ?? 0) > ($match->set2_player2_points ?? 0)) $p1_sets++;
                            if(($match->set2_player2_points ?? 0) > ($match->set2_player1_points ?? 0)) $p2_sets++;

                            if(!is_null($match->set3_player1_points) && !is_null($match->set3_player2_points)){
                                if($match->set3_player1_points > $match->set3_player2_points) $p1_sets++;
                                if($match->set3_player2_points > $match->set3_player1_points) $p2_sets++;
                            }

                            $winner = $p1_sets > $p2_sets ? optional($match->player1)->name 
                                     : ($p2_sets > $p1_sets ? optional($match->player2)->name : 'Draw');
                        @endphp
                        {{ $winner }}
                    </td>

                    <td>
                        {{-- Save Button --}}
                        <button type="submit" class="btn btn-sm btn-success">Save</button>
                </form>
                        {{-- Delete Button --}}
                        <form action="{{ route('matches.singles.deleteSingle', $match->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" 
                                onclick="return confirm('Delete this match?');">
                                Delete
                            </button>
                        </form>
                    </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center">
        {{ $matches->links() }}
    </div>
</div>
@endsection
