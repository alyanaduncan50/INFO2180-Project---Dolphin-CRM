<?php
session_start();

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ensure the user is logged in and session ID exists
if (!isset($_SESSION['id'])) {
    echo json_encode(['error' => 'User not logged in.']);
    exit();
}

$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'dolphin_crm';

// Connect to the database
$mysqli = new mysqli($host, $user, $password, $dbname);

if ($mysqli->connect_error) {
    echo json_encode(['error' => 'Database connection failed: ' . $mysqli->connect_error]);
    exit();
}

// Check if contact_id is sent via POST
if (!isset($_POST['contact_id'])) {
    echo json_encode(['error' => 'Contact ID is required.']);
    exit();
}

$contact_id = intval($_POST['contact_id']);
$user_id = $_SESSION['id']; // Current user's ID from the session

// Update 'assigned_to' field with the current user's ID
$query = $mysqli->prepare("UPDATE contacts SET assigned_to = ? WHERE id = ?");
if (!$query) {
    echo json_encode(['error' => 'Prepared statement failed: ' . $mysqli->error]);
    exit();
}

$query->bind_param("ii", $user_id, $contact_id);

if ($query->execute()) {
    echo json_encode(['success' => 'Contact successfully assigned to you.']);
} else {
    echo json_encode(['error' => 'Failed to assign contact: ' . $query->error]);
}

$query->close();
$mysqli->close();
?>
