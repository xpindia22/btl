@extends('layouts.app')

@section('content')
<div class="container">
    <h2>My Pinned Items</h2>
    <ul class="list-group">
        @forelse ($favorites as $favorite)
            @php
                $route = favorite_route_name($favorite->favoritable_type);
            @endphp

            <li class="list-group-item">
                @if($route)
                    <a href="{{ route($route, $favorite->favoritable_id) }}">
                        {{ class_basename($favorite->favoritable_type) }} #{{ $favorite->favoritable_id }}
                    </a>
                @else
                    <span class="text-muted">Unknown Item</span>
                @endif
            </li>
        @empty
            <li class="list-group-item text-muted">No items pinned yet.</li>
        @endforelse
    </ul>
</div>
@endsection
