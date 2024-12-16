document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');

    form.addEventListener('submit', (e) => {
        e.preventDefault(); // Prevent default form submission

        const formData = new FormData(form);

        fetch('php/add_user.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("✅ User added successfully!"); // Success alert
                form.reset(); // Reset the form fields
            } else {
                alert(`❌ Error: ${data.error || "Failed to add user."}`); // Error alert
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert("❌ An unexpected error occurred. Please try again.");
        });
    });
});
