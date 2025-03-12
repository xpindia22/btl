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
                        <td>{{ $player->dob }}</td>
                        <td>{{ $player->age }}</td>
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
{{-- Pagination --}}
<div class="d-flex justify-content-center">
    {{ $players->appends(request()->query())->links('vendor.pagination.semantic-ui') }}
</div>

</div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".favorite-btn").forEach(button => {
            button.addEventListener("click", function (event) {
                event.preventDefault();

                let buttonElement = this;
                let form = buttonElement.closest(".favorite-form");
                let itemId = form.dataset.id;
                let itemType = form.dataset.type;
                let token = document.querySelector('meta[name="csrf-token"]').getAttribute("content");

                fetch("{{ route('favorites.toggle') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": token
                    },
                    body: JSON.stringify({
                        favoritable_id: itemId,
                        favoritable_type: itemType
                    })
                })
                .then(response => response.json())
                .then(data => {
                    buttonElement.classList.toggle("btn-success", data.status === "pinned");
                    buttonElement.classList.toggle("btn-primary", data.status === "unpinned");
                    buttonElement.innerHTML = data.status === "pinned" ? "⭐ Pinned" : "📌 Pin";
                })
                .catch(error => console.error("Error:", error));
            });
        });
    });
</script>
@endsection
