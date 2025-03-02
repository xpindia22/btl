@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Players</h2>
    
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th> <!-- Serial Number Column -->
                <th>UID</th>
                <th>Name</th>
                <th>DOB</th>
                <th>Sex</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($players as $index => $player)
            <tr data-uid="{{ $player->uid }}">
                <td>{{ $index + 1 }}</td> <!-- Serial Number -->
                <td class="editable" data-field="uid">{{ $player->uid }}</td>
                <td class="editable" data-field="name">{{ $player->name }}</td>
                <td class="editable" data-field="dob">{{ $player->dob }}</td>
                <td class="editable" data-field="sex">{{ $player->sex }}</td>
                <td>
                    <button class="btn btn-primary btn-sm edit-btn">Edit</button>
                    <button class="btn btn-danger btn-sm delete-btn">Delete</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const csrfToken = '{{ csrf_token() }}';

    document.addEventListener("click", function (event) {
        let target = event.target;

        // Handle Edit/Save button clicks
        if (target.classList.contains("edit-btn")) {
            let row = target.closest("tr");
            let uid = row.dataset.uid;

            row.querySelectorAll(".editable").forEach(cell => {
                let value = cell.innerText.trim();
                let input = document.createElement("input");
                input.type = "text";
                input.value = value;
                input.dataset.field = cell.dataset.field;
                cell.innerHTML = "";
                cell.appendChild(input);
            });

            target.textContent = "Save";
            target.classList.remove("edit-btn");
            target.classList.add("save-btn");
        } 
        else if (target.classList.contains("save-btn")) {
            let row = target.closest("tr");
            let uid = row.dataset.uid;
            let data = {};

            row.querySelectorAll(".editable input").forEach(input => {
                data[input.dataset.field] = input.value.trim();
            });

            console.log("Updating UID:", uid, "with data:", data); // Debugging log

            fetch(`/players/${uid}/update`, {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(responseData => {
                console.log("Response:", responseData); // Debugging log
                if (responseData.success) {
                    row.querySelectorAll(".editable").forEach(cell => {
                        let field = cell.dataset.field;
                        cell.innerHTML = responseData.player[field]; // Update table with new values
                    });

                    target.textContent = "Edit";
                    target.classList.remove("save-btn");
                    target.classList.add("edit-btn");
                } else {
                    alert("Update failed: " + responseData.message);
                }
            })
            .catch(error => console.error("Error:", error));
        }

        // Handle Delete button clicks
        else if (target.classList.contains("delete-btn")) {
            let row = target.closest("tr");
            let uid = row.dataset.uid;

            if (!confirm("Are you sure you want to delete this player?")) return;

            fetch(`/players/${uid}/delete`, {
                method: "DELETE",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken
                }
            })
            .then(response => response.json())
            .then(responseData => {
                console.log("Delete Response:", responseData); // Debugging log
                if (responseData.success) {
                    row.remove(); // Remove row from UI
                } else {
                    alert("Delete failed!");
                }
            })
            .catch(error => console.error("Error:", error));
        }
    });
});
</script>

@endsection
