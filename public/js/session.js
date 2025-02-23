// let sessionExpired = false;
// let focusLostTime = null;

// window.addEventListener("blur", () => {
//     focusLostTime = new Date();
// });

// window.addEventListener("focus", () => {
//     if (focusLostTime) {
//         const elapsedTime = (new Date() - focusLostTime) / 1000; // Time in seconds
//         if (elapsedTime > 300 && !sessionExpired) { // 300 seconds = 5 minutes
//             fetch("logout.php", { method: "POST" })
//                 .then(() => {
//                     sessionExpired = true;
//                     alert("Your session has expired due to inactivity. Please reauthenticate.");
//                     window.location.href = "login.php";
//                 })
//                 .catch((error) => console.error("Error ending session:", error));
//         }
//     }
// });

let sessionExpired = false;
let focusLostTime = null;

window.addEventListener("blur", () => {
    focusLostTime = new Date();
});

window.addEventListener("focus", () => {
    if (focusLostTime) {
        const elapsedTime = (new Date() - focusLostTime) / 1000; // Time in seconds
        if (elapsedTime > 300 && !sessionExpired) { // 300 seconds = 5 minutes
            fetch("/logout", { // ✅ Use Laravel logout route
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                }
            })
            .then(() => {
                sessionExpired = true;
                alert("Your session has expired due to inactivity. Please reauthenticate.");
                window.location.href = "/login"; // ✅ Correct Laravel redirect
            })
            .catch((error) => console.error("Error ending session:", error));
        }
    }
});

