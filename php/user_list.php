<?php
session_start();
    
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    if (!isset($_SESSION['email'])) {
        header("Location: ../login.html");
        exit();
    }

    $host = 'localhost';
    $user = 'root';
    $password = '';
    $dbname = 'dolphin_crm';

header('Content-Type: application/json');
$mysqli = new mysqli("localhost", $user, $password, $dbname);

if ($mysqli->connect_error) {
    echo json_encode(['error' => 'Database connection failed: ' . $mysqli->connect_error]);
    exit();
}

$query = "SELECT firstname, lastname, email, role, created_at FROM Users ORDER BY created_at DESC";
$result = $mysqli->query($query);

if ($result) {
    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    echo json_encode($users); 
} else {
    echo json_encode(['error' => 'Failed to fetch users']);
}

$mysqli->close();
?>
