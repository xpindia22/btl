@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Select Ranking Type</h2>
    <div class="mb-3">
    <a href="{{ route('players.ranking') }}" class="{{ request()->routeIs('players.ranking') ? 'active' : '' }}">
    ⭐ Singles Ranking
</a>

<a href="{{ route('players.doublesRanking') }}" class="{{ request()->routeIs('players.doublesRanking') ? 'active' : '' }}">
    ⭐ Doubles Ranking
</a>

    </div>
</div>
@endsection
