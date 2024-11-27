document.getElementById("loginForm").addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent the default form submission

    // Get form input values
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;

    // Create a POST request
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "http://localhost:3000/login", true);
    xhr.setRequestHeader("Content-Type", "application/json");

    // Handle the response
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                // Parse and handle the response
                const response = JSON.parse(xhr.responseText);
                alert("Login successful!");
                console.log("Token:", response.token);
                // Optionally, redirect or store the token
                window.location.href = "dashboard.html";
            } else {
                // Handle errors
                const response = JSON.parse(xhr.responseText);
                alert(response.error || "Login failed.");
            }
        }
    };

    // Send the login data
    const data = JSON.stringify({ email: email, password: password });
    xhr.send(data);
});