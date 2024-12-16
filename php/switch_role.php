<?php
session_start();

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost';
$dbname = 'dolphin_crm';
$username = 'root';
$password = '';

// Validate request
if (!isset($_POST['contact_id'], $_POST['type'])) {
    echo json_encode(['error' => 'Contact ID and new type are required.']);
    exit();
}

$contactId = intval($_POST['contact_id']);
$newType = htmlspecialchars(trim($_POST['type']));

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Update the type field
    $stmt = $pdo->prepare("UPDATE contacts SET type = :type, updated_at = NOW() WHERE id = :contact_id");
    $stmt->execute([
        ':type' => $newType,
        ':contact_id' => $contactId
    ]);

    echo json_encode(['success' => "Role switched to $newType"]);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Failed to switch role: ' . $e->getMessage()]);
    exit();
}
?>
