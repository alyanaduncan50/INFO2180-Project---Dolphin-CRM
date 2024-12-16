<?php
session_start();

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'dolphin_crm';

$mysqli = new mysqli($host, $user, $password, $dbname);

if ($mysqli->connect_error) {
    echo json_encode(['error' => 'Database connection failed: ' . $mysqli->connect_error]);
    exit();
}

if (!isset($_POST['firstname'], $_POST['lastname'], $_POST['email'], $_POST['password'], $_POST['role'])) {
    echo json_encode(['error' => 'All fields are required.']);
    exit();
}

$firstname = htmlspecialchars(trim($_POST['firstname']));
$lastname = htmlspecialchars(trim($_POST['lastname']));
$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
$password_raw = $_POST['password'];
$role = htmlspecialchars(trim($_POST['role']));

if (!$email) {
    echo json_encode(['error' => 'Invalid email format.']);
    exit();
}

$password_hashed = password_hash($password_raw, PASSWORD_BCRYPT);

$query = $mysqli->prepare("INSERT INTO Users (firstname, lastname, email, password, role) VALUES (?, ?, ?, ?, ?)");
if (!$query) {
    echo json_encode(['error' => 'Prepared statement failed: ' . $mysqli->error]);
    exit();
}

$query->bind_param("sssss", $firstname, $lastname, $email, $password_hashed, $role);

if ($query->execute()) {
    echo json_encode(['success' => 'User successfully added.']);
} else {
    echo json_encode(['error' => 'Failed to add user: ' . $query->error]);
}

$query->close();
$mysqli->close();
?>
