<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html"); // Redirect to login page if not logged in
    exit();
}

// Serve the existing dashboard.html file
echo file_get_contents("../dashboard.html");
?>
