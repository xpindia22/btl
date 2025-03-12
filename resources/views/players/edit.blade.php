@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Players</h2>
    
    <table class="registration-form-1400 mb-3 table table-bordered">
        <thead>
            <tr>
                <th>S No</th>
                <th>UID</th>
                <th>Name</th>
                <th>Email</th>
                <th>DOB</th>
                <th>Age</th>
                <th>Sex</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($players as $index => $player)
            <tr data-uid="{{ $player->uid }}">
                <td>{{ $players instanceof \Illuminate\Pagination\LengthAwarePaginator ? $players->firstItem() + $index : $index + 1 }}</td>
                <td class="editable" data-field="uid">{{ $player->uid }}</td>
                <td class="editable" data-field="name">{{ $player->name }}</td>
                <td class="editable" data-field="email">{{ $player->email }}</td>
                <td class="editable" data-field="dob">{{ $player->dob }}</td>
                <td class="age">{{ \Carbon\Carbon::parse($player->dob)->age }}</td>
                <td class="editable" data-field="sex">{{ $player->sex }}</td>
                <td>
                    <button class="btn btn-success btn-sm save-btn" style="display:none;">Save</button>
                    <button class="btn btn-primary btn-sm edit-btn">Edit</button>
                    <button class="btn btn-danger btn-sm delete-btn">Delete</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center">
        {{ $players->appends(request()->query())->links('vendor.pagination.semantic-ui') }}
    </div>
</div>

{{-- Include SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const csrfToken = '{{ csrf_token() }}';
    const baseUrl = "{{ url('players') }}";

    // Function to show SweetAlert2 notifications
    function showNotification(type, message) {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: type,
            title: message,
            showConfirmButton: false,
            timer: 3000
        });
    }

    // Turn table cells into input fields for editing
    document.querySelectorAll(".edit-btn").forEach(button => {
        button.addEventListener("click", function () {
            let row = this.closest("tr");
            row.querySelectorAll(".editable").forEach(cell => {
                let value = cell.innerText.trim();
                let input = document.createElement("input");
                input.type = "text";
                input.value = value;
                input.dataset.field = cell.dataset.field;
                cell.innerHTML = "";
                cell.appendChild(input);
            });
            row.querySelector(".save-btn").style.display = "inline-block";
            this.style.display = "none";
        });
    });

    // Save changes via a PUT request
    document.querySelectorAll(".save-btn").forEach(button => {
        button.addEventListener("click", function () {
            let row = this.closest("tr");
            let uid = row.dataset.uid;
            let data = {};

            row.querySelectorAll(".editable input").forEach(input => {
                data[input.dataset.field] = input.value.trim();
            });

            fetch(`${baseUrl}/${uid}/update`, {
                method: "PUT",
                headers: {
                    "Accept": "application/json",
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(responseData => {
                if (responseData.success) {
                    row.querySelectorAll(".editable").forEach(cell => {
                        let field = cell.dataset.field;
                        cell.innerHTML = responseData.player[field];
                    });
                    // Update the age column based on new DOB
                    let newAge = calculateAge(responseData.player.dob);
                    row.querySelector(".age").textContent = newAge;
                    row.querySelector(".edit-btn").style.display = "inline-block";
                    row.querySelector(".save-btn").style.display = "none";
                    showNotification("success", "Player updated successfully!");
                } else {
                    showNotification("error", "Update failed: " + responseData.message);
                }
            })
            .catch(error => {
                console.error("Error updating player:", error);
                showNotification("error", "An error occurred while updating.");
            });
        });
    });

    // Delete player via a DELETE request
    document.querySelectorAll(".delete-btn").forEach(button => {
        button.addEventListener("click", function () {
            let row = this.closest("tr");
            let uid = row.dataset.uid;

            Swal.fire({
                title: "Are you sure?",
                text: "This player will be permanently deleted!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`${baseUrl}/${uid}/delete`, {
                        method: "DELETE",
                        headers: {
                            "Accept": "application/json",
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": csrfToken
                        }
                    })
                    .then(response => response.json())
                    .then(responseData => {
                        if (responseData.success) {
                            row.remove();
                            showNotification("success", "Player deleted successfully!");
                        } else {
                            showNotification("error", "Delete failed: " + responseData.message);
                        }
                    })
                    .catch(error => {
                        console.error("Error deleting player:", error);
                        showNotification("error", "An error occurred while deleting.");
                    });
                }
            });
        });
    });

    // Helper function to calculate age from DOB
    function calculateAge(dob) {
        let birthDate = new Date(dob);
        let today = new Date();
        let age = today.getFullYear() - birthDate.getFullYear();
        let monthDiff = today.getMonth() - birthDate.getMonth();
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        return age;
    }
});
</script>
@endsection
