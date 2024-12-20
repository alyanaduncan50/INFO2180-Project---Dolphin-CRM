document.addEventListener("DOMContentLoaded", () => {
    // Fetch users for the "Assigned To" dropdown
    async function fetchUsers() {
        const response = await fetch("php/get_user_dropdown.php");
        const users = await response.json();

        const assignedTo = document.getElementById("assigned_to");
        assignedTo.innerHTML = ""; 

        if (response.ok) {
            users.forEach(user => {
                const option = document.createElement("option");
                option.value = user.id;
                option.textContent = `${user.firstname} ${user.lastname}`;
                assignedTo.appendChild(option);
            });
        } else {
            const option = document.createElement("option");
            option.value = "";
            option.textContent = "Failed to load users";
            assignedTo.appendChild(option);
            console.error("Error fetching users:", users.message);
        }
    }

    // Submit the form
    document.getElementById("newContactForm").addEventListener("submit", async function (e) {
        e.preventDefault();

        const formData = new FormData(this);

        const response = await fetch("php/new_contact.php", {
            method: "POST",
            body: JSON.stringify(Object.fromEntries(formData)),
            headers: { "Content-Type": "application/json" },
        });

        const result = await response.json();

        if (response.ok) {
            alert("✅ Contact successfully created!");
            this.reset();
            fetchUsers();
        } else {
            alert(`❌ Error: ${result.message}`);
        }
    });

    fetchUsers(); // Load users when the page loads
});
