let sessionExpired = false;
let focusLostTime = null;

window.addEventListener("blur", () => {
    focusLostTime = new Date();
});

window.addEventListener("focus", () => {
    if (focusLostTime) {
        const elapsedTime = (new Date() - focusLostTime) / 1000; // Convert to seconds
        if (elapsedTime > 100 && !sessionExpired) { // 🔹 Change 300 to 600 (10 minutes)
            fetch("/btl/logout", { // ✅ Updated to match Laravel logout route
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                },
                credentials: "same-origin" // ✅ Ensures it works with Laravel authentication
            })
            .then((response) => {
                if (response.ok) {
                    sessionExpired = true;
                    alert("Dear User, your session has expired due to inactivity. Please reauthenticate.");
                    window.location.href = "/btl/login"; // ✅ Redirect to correct login page
                } else {
                    console.error("Logout request failed:", response);
                }
            })
            .catch((error) => console.error("Error ending session:", error));
        }
    }
});
