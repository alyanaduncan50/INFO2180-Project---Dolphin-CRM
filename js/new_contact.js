document.getElementById("newContactForm").addEventListener("submit", async function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    const response = await fetch("php/add_contact.php", {
        method: "POST",
        body: JSON.stringify(Object.fromEntries(formData)),
        headers: { "Content-Type": "application/json" },
    });

    const result = await response.json();

    const feedback = document.getElementById("feedback");
    if (response.ok) {
        feedback.textContent = "Contact successfully created!";
        feedback.style.color = "green";
        this.reset();
    } else {
        feedback.textContent = `Error: ${result.message}`;
        feedback.style.color = "red";
    }
});

async function fetchUsers() {
    const response = await fetch("php/get_users.php");
    const users = await response.json();

    const assignedTo = document.getElementById("assigned_to");
    assignedTo.innerHTML = ""; // Clear previous options

    users.forEach(user => {
        const option = document.createElement("option");
        option.value = user.id;
        option.textContent = `${user.firstname} ${user.lastname}`;
        assignedTo.appendChild(option);
    });
}

fetchUsers();
