<?php
session_start();

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost';
$dbname = 'dolphin_crm';
$username = 'root';
$password = '';

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    echo json_encode(['error' => 'User not logged in.']);
    exit();
}

if (!isset($_POST['contact_id'])) {
    echo json_encode(['error' => 'Contact ID is required.']);
    exit();
}

$loggedInUserId = $_SESSION['id']; 
$contactId = intval($_POST['contact_id']);

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("UPDATE contacts SET assigned_to = :assigned_to, updated_at = NOW() WHERE id = :contact_id");
    $stmt->execute([
        ':assigned_to' => $loggedInUserId,
        ':contact_id' => $contactId,
    ]);

    echo json_encode(['success' => 'Contact successfully assigned to you.']);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Failed to assign contact: ' . $e->getMessage()]);
}
?>
