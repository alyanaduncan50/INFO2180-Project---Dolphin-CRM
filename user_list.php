<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'Admin') {
    header('Location: login.html'); // Redirect to login if unauthorized
    exit();
}

// Database connection configuration
$host = 'localhost';
$dbname = 'dolphin_crm';
$username = 'root';
$password = '';

// Create database connection
$mysqli = new mysqli($host, $username, $password, $dbname);

// Check database connection
if ($mysqli->connect_error) {
    die("Database connection failed: " . $mysqli->connect_error);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dolphin CRM - User List</title>
    <link rel="stylesheet" href="user_list.css">
    <script src="js/adduserbtn.js"></script>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <img src="img/logo.png" alt="Logo">
        <span class="navbar-title">Dolphin CRM</span>
    </div>

    <!-- Layout -->
    <div class="layout">
        <!-- Sidebar -->
        <div class="sidebar">
            <ul>
                <li>
                    <img src="img/home_icon.png" alt="Home">
                    <a href="dashboard.html">Home</a>
                </li>
                <li>
                    <img src="img/new_contact_icon.png" alt="New Contact">
                    <a href="new_contact.html">New Contact</a>
                </li>
                <li>
                    <img src="img/users_icon.png" alt="Users">
                    <a href="users.html" class="active">Users</a> <!-- Active link -->
                </li>
                <li>
                    <img src="img/logout_icon.png" alt="Logout">
                    <a href="php/logout.php">Logout</a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="content">
            <h1>Users</h1>
            <button>+ Add User</button>
            <div class="user-list">
                <table class="user-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Date Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Rows will be dynamically injected -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
