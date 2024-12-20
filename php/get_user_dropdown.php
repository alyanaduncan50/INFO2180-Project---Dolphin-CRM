<?php
header('Content-Type: application/json');

$host = 'localhost'; 
$dbname = 'dolphin_crm';
$username = 'root'; 
$password = ''; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("SELECT id, firstname, lastname FROM contacts");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($users);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Failed to fetch users: ' . $e->getMessage()]);
    exit();
}
?>
