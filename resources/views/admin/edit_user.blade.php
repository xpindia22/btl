@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Manage Users</h2>

    @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>  <!-- User ID Column -->
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td contenteditable="true" 
                        data-id="{{ $user->id }}" 
                        data-field="username"
                        class="editable">{{ $user->username }}</td>
                    <td contenteditable="true" 
                        data-id="{{ $user->id }}" 
                        data-field="email"
                        class="editable">{{ $user->email }}</td>
                    <td>
                        <select class="role-select" data-id="{{ $user->id }}">
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                            <option value="visitor" {{ $user->role == 'visitor' ? 'selected' : '' }}>Visitor</option>
                        </select>
                    </td>
                    <td>
                        <!-- Edit Button for a detailed edit page -->
                        <a href="{{ route('admin.edit_users.edit', $user->id) }}" class="btn btn-primary btn-sm">Edit</a>
                        <!-- Delete Button -->
                        <button class="btn btn-danger btn-sm delete-user" data-id="{{ $user->id }}">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- JavaScript for Inline Editing & Deleting -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Inline editing for username & email
    document.querySelectorAll(".editable").forEach(function(element) {
        element.addEventListener("blur", function() {
            let userId = this.getAttribute("data-id");
            let field = this.getAttribute("data-field");
            let value = this.innerText.trim();
            updateUser(userId, field, value);
        });
    });

    // Role selection change event
    document.querySelectorAll(".role-select").forEach(function(element) {
        element.addEventListener("change", function() {
            let userId = this.getAttribute("data-id");
            let value = this.value;
            updateUser(userId, "role", value);
        });
    });

    // Function to send AJAX request for updating a user
    function updateUser(userId, field, value) {
        // Construct URL using Laravel's URL helper
        let url = "{{ url('/admin/edit_users') }}/" + userId;
        fetch(url, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ [field]: value })
        })
        .then(function(response) {
            return response.json();
        })
        .then(function(data) {
            if (data.success) {
                console.log("User updated successfully");
            } else {
                alert("Failed to update user: " + (data.message || "Unknown error"));
            }
        })
        .catch(function(error) {
            console.error("Error:", error);
        });
    }

    // Delete user
    document.querySelectorAll(".delete-user").forEach(function(button) {
        button.addEventListener("click", function() {
            let userId = this.getAttribute("data-id");
            if (confirm("Are you sure you want to delete this user?")) {
                let url = "{{ url('/admin/edit_users') }}/" + userId;
                fetch(url, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    }
                })
                .then(function(response) {
                    return response.json();
                })
                .then(function(data) {
                    if (data.success) {
                        alert("User deleted successfully");
                        location.reload();
                    } else {
                        alert("Failed to delete user: " + (data.message || "Unknown error"));
                    }
                })
                .catch(function(error) {
                    console.error("Error:", error);
                });
            }
        });
    });
});
</script>
@endsection
