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
                        if (field === 'dob') {
                            // Instead of using Date, extract first 10 characters from the timestamp
                            let formattedDOB = responseData.player[field].substring(0, 10);
                            cell.innerHTML = formattedDOB;
                        } else {
                            cell.innerHTML = responseData.player[field];
                        }
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
        // We assume dob is in the format YYYY-MM-DD or full timestamp, 
        // so we extract the date part.
        let formattedDob = dob.substring(0, 10);
        let birthDate = new Date(formattedDob);
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
