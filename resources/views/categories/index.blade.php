@extends('layouts.app')

@section('content')
<div class="container">
    <h1>All Categories</h1>

    @if(session('message'))
         <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    <table class="table table-striped">
         <thead>
              <tr>
                   <th><a href="?order_by=id&order_dir={{ $next_order_dir }}">ID</a></th>
                   <th><a href="?order_by=name&order_dir={{ $next_order_dir }}">Name</a></th>
                   <th><a href="?order_by=age_group&order_dir={{ $next_order_dir }}">Age Group</a></th>
                   <th><a href="?order_by=sex&order_dir={{ $next_order_dir }}">Sex</a></th>
                   <th><a href="?order_by=creator_name&order_dir={{ $next_order_dir }}">Created By</a></th>
                   <th>Actions</th>
                   <th>Favorite</th>
              </tr>
         </thead>
         <tbody>
              @foreach($categories as $category)
              <tr>
                   <td>{{ $category->id }}</td>
                   <td>{{ $category->name }}</td>
                   <td>{{ $category->age_group }}</td>
                   <td>{{ $category->sex }}</td>
                   <td>{{ $category->creator_name ?? 'N/A' }}</td>
                   <td>
                        <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display:inline-block;">
                             @csrf
                             @method('DELETE')
                             <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this category?')">Delete</button>
                        </form>
                   </td>
                   <td>
                        <form class="favorite-form" data-id="{{ $category->id }}" data-type="App\Models\Category">
                             @csrf
                             <button type="button"
                                 class="btn btn-sm favorite-btn {{ $category->isFavoritedByUser(auth()->id()) ? 'btn-success' : 'btn-primary' }}">
                                 {{ $category->isFavoritedByUser(auth()->id()) ? '⭐ Pinned' : '📌 Pin' }}
                             </button>
                        </form>
                   </td>
              </tr>
              @endforeach
         </tbody>
    </table>
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
