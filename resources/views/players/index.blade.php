@extends('layouts.app')

@section('content')
<div class="container my-5 p-4 shadow-sm bg-white rounded">
    <h1 class="mb-3">Registered Players</h1>

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>UID</th>
                    <th>Name</th>
                    <th>Date of Birth</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>Registered At</th>
                    <th>Favorite</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($players as $player)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $player->uid }}</td>
                        <td>{{ $player->name }}</td>
                        <td>{{ \Carbon\Carbon::parse($player->dob)->format('Y-m-d') }}</td>
                        <td>{{ \Carbon\Carbon::parse($player->dob)->age }}</td>
                        <td>{{ $player->sex }}</td>
                        <td>{{ date("d-m-Y h:i A", strtotime($player->created_at)) }}</td>
                        <td>
                            <form class="favorite-form" data-id="{{ $player->id }}" data-type="App\Models\Player">
                                @csrf
                                <button type="button"
                                    class="btn btn-sm favorite-btn {{ $player->isFavoritedByUser(auth()->id()) ? 'btn-success' : 'btn-primary' }}">
                                    {{ $player->isFavoritedByUser(auth()->id()) ? '⭐ Pinned' : '📌 Pin' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center">
        {{ $players->appends(request()->query())->links('vendor.pagination.semantic-ui') }}
    </div>
</div>
@endsection
