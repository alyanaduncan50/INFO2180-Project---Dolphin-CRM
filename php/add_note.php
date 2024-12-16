<?php
session_start();

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['id'])) {
    echo json_encode(['error' => 'User not logged in.']);
    exit();
}

$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'dolphin_crm';

$mysqli = new mysqli($host, $user, $password, $dbname);

if ($mysqli->connect_error) {
    echo json_encode(['error' => 'Database connection failed: ' . $mysqli->connect_error]);
    exit();
}

if (!isset($_POST['contact_id'], $_POST['comment'])) {
    echo json_encode(['error' => 'Contact ID and comment are required.']);
    exit();
}

$contact_id = intval($_POST['contact_id']);
$comment = htmlspecialchars(trim($_POST['comment'])); 
$created_by = $_SESSION['id'];

if (empty($comment)) {
    echo json_encode(['error' => 'Comment cannot be empty.']);
    exit();
}

$insertNote = $mysqli->prepare("INSERT INTO Notes (contact_id, comment, created_by) VALUES (?, ?, ?)");

if (!$insertNote) {
    echo json_encode(['error' => 'Insert statement failed: ' . $mysqli->error]);
    exit();
}

$insertNote->bind_param("isi", $contact_id, $comment, $created_by);

if ($insertNote->execute()) {
    $updateContact = $mysqli->prepare("UPDATE Contacts SET updated_at = CURRENT_TIMESTAMP WHERE id = ?");
    if ($updateContact) {
        $updateContact->bind_param("i", $contact_id);
        $updateContact->execute();
        $updateContact->close();
    } else {
        echo json_encode(['error' => 'Failed to update contact: ' . $mysqli->error]);
        exit();
    }
    echo json_encode(['success' => 'Note added successfully.']);
} else {
    echo json_encode(['error' => 'Failed to insert note: ' . $insertNote->error]);
}

$insertNote->close();
$mysqli->close();
?>
