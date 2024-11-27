<?php
// Start a session
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dolphin_crm";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Query the database
    $sql = "SELECT * FROM Users WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Prepared statement failed: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            // Redirect to dashboard
            header("Location: ../dashboard.html");
            exit();
        } else {
            // Password does not match
            echo "<script>
                alert('Invalid email or password.');
                window.location.href = '../login.html';
            </script>";
            exit();
        }
    } else {
        // No user found
        echo "<script>
            alert('No user found with the given email.');
            window.location.href = '../login.html';
        </script>";
        exit();
    }

    $stmt->close();
}

$conn->close();
?>
