<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Redirect to login page if not logged in
if (!isset($_SESSION['email'])) {
    header("Location: ../login.html");
    exit();
}

// Database credentials
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'dolphin_crm';

header('Content-Type: application/json');
$mysqli = new mysqli("localhost", "root", "", "dolphin_crm");

if ($mysqli->connect_error) {
    echo json_encode(['error' => 'Database connection failed: ' . $mysqli->connect_error]);
    exit();
}

// Check for ID in the query string
if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'No contact ID provided.']);
    exit();
}

$contactId = intval($_GET['id']);

// Fetch contact and notes
$sql = "
    SELECT c.*, n.created_by AS note_created_by, n.created_at AS note_created_at, n.comment AS note_comment
    FROM contacts c
    LEFT JOIN notes n ON c.id = n.contact_id
    WHERE c.id = ?
";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $contactId);
$stmt->execute();
$result = $stmt->get_result();

$contact = null;

while ($row = $result->fetch_assoc()) {
    if (!$contact) {
        $contact = [
            'id' => $row['id'],
            'title' => $row['title'],
            'firstname' => $row['firstname'],
            'lastname' => $row['lastname'],
            'email' => $row['email'],
            'telephone' => $row['telephone'],
            'company' => $row['company'],
            'type' => $row['type'],
            'assigned_to' => $row['assigned_to'],
            'created_at' => $row['created_at'],
            'updated_at' => $row['updated_at'],
            'notes' => []
        ];
    }

    if ($row['note_comment']) {
        $contact['notes'][] = [
            'addedBy' => $row['note_created_by'],
            'date' => $row['note_created_at'],
            'comment' => $row['note_comment']
        ];
    }
}

echo json_encode($contact);
$mysqli->close();
?>