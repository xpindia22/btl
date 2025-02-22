@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Manage Users --- <a href="/users">View Users</a>
    </h2>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Mobile No</th>
                <th>Role</th>
                <th>Moderator</th> <!-- ✅ Editable -->
                <th>Creator</th> <!-- ✅ Editable -->
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>
                        <form method="POST" action="{{ route('users.update', $user->id) }}">
                            @csrf
                            @method('PUT')
                            <input type="text" name="username" value="{{ $user->username }}" required>
                    </td>
                    <td><input type="email" name="email" value="{{ $user->email }}" required></td>
                    <td><input type="text" name="mobile_no" value="{{ $user->mobile_no }}"></td>
                    <td>
                        <select name="role" class="form-control">
                            <option value="visitor" {{ $user->role === 'visitor' ? 'selected' : '' }}>Visitor</option>
                            <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </td>

                    <!-- ✅ Editable "Moderator Of" Column -->
                    <td>
                        <select name="moderated_tournaments[]" class="form-control" multiple>
                            @foreach ($tournaments as $tournament)
                                <option value="{{ $tournament->id }}"
                                    {{ in_array($tournament->id, $user->moderatedTournaments->pluck('id')->toArray()) ? 'selected' : '' }}>
                                    {{ $tournament->name }} ({{ $tournament->year }})
                                </option>
                            @endforeach
                        </select>
                    </td>

                    <!-- ✅ Editable "Creator Of" Column -->
                    <td>
                        <select name="created_tournaments[]" class="form-control" multiple>
                            @foreach ($tournaments as $tournament)
                                <option value="{{ $tournament->id }}"
                                    {{ in_array($tournament->id, $user->createdTournaments->pluck('id')->toArray()) ? 'selected' : '' }}>
                                    {{ $tournament->name }} ({{ $tournament->year }})
                                </option>
                            @endforeach
                        </select>
                    </td>

                    <td>
                        <button type="submit" class="btn btn-success btn-sm">Update</button>
                        </form> <!-- ✅ Properly closed form -->

                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- ✅ Centered Pagination -->
    <div class="d-flex justify-content-center">
        {{ $users->appends(request()->query())->links('vendor.pagination.default') }}
    </div>

    <!-- ✅ Button to Create a New User -->
    <a href="{{ route('users.create') }}" class="btn btn-success mt-3">Create New User</a>

</div>
<<<<<<< HEAD

<!-- ✅ JavaScript for Inline Editing & Deleting -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Inline editing for username & email
    document.querySelectorAll(".editable").forEach((element) => {
        element.addEventListener("blur", function() {
            let userId = this.getAttribute("data-id");
            let field = this.getAttribute("data-field");
            let value = this.innerText.trim();

            updateUser(userId, field, value);
        });
    });

    // Role selection change event
    document.querySelectorAll(".role-select").forEach((element) => {
        element.addEventListener("change", function() {
            let userId = this.getAttribute("data-id");
            let value = this.value;
            updateUser(userId, "role", value);
        });
    });

    // Function to send AJAX request for updating users
    function updateUser(userId, field, value) {
        fetch(`/admin/edit_users/${userId}`, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ [field]: value })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log("User updated successfully");
            } else {
                alert("Failed to update user");
            }
        })
        .catch(error => console.error("Error:", error));
    }

    // Delete user
    document.querySelectorAll(".delete-user").forEach((button) => {
        button.addEventListener("click", function() {
            let userId = this.getAttribute("data-id");

            if (confirm("Are you sure you want to delete this user?")) {
                fetch(`/admin/edit_users/${userId}`, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("User deleted successfully");
                        location.reload();
                    } else {
                        alert("Failed to delete user");
                    }
                })
                .catch(error => console.error("Error:", error));
            }
        });
    });
});
</script>
 
=======
>>>>>>> 7c8a4f89609b7fd184c29bf695eae0ea12f8cc35
@endsection
