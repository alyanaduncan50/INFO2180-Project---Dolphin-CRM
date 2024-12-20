<?php
session_start();
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost';
$dbname = 'dolphin_crm';
$username = 'root';
$password = '';

if (!isset($_SESSION['id'])) {
    echo json_encode(['error' => 'User not logged in.']);
    exit();
}

if (!isset($_POST['contact_id'], $_POST['comment'])) {
    echo json_encode(['error' => 'Contact ID and comment are required.']);
    exit();
}

$contactId = intval($_POST['contact_id']);
$comment = htmlspecialchars(trim($_POST['comment']));
$createdBy = $_SESSION['id'];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("INSERT INTO Notes (contact_id, comment, created_by, created_at) VALUES (:contact_id, :comment, :created_by, NOW())");
    $stmt->execute([
        ':contact_id' => $contactId,
        ':comment' => $comment,
        ':created_by' => $createdBy
    ]);

    echo json_encode(['success' => 'Note added successfully.']);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Failed to add note: ' . $e->getMessage()]);
}
?>
