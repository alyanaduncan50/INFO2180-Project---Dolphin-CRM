<?php
// Database configuration
$host = 'localhost'; 
$dbname = 'dolphin_crm';
$username = 'root'; 
$password = ''; 

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get raw POST data
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate required fields
    $requiredFields = ['title', 'firstname', 'lastname', 'email', 'type', 'assigned_to'];
    foreach ($requiredFields as $field) {
        if (empty($input[$field])) {
            http_response_code(400);
            echo json_encode(['message' => "$field is required."]);
            exit;
        }
    }

    // Prepare SQL statement
    $sql = "INSERT INTO contacts (title, firstname, lastname, email, telephone, company, type, assigned_to, created_by, created_at, updated_at) 
            VALUES (:title, :firstname, :lastname, :email, :telephone, :company, :type, :assigned_to, :created_by, NOW(), NOW())";

    try {
        // Insert data into the database
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':title' => $input['title'],
            ':firstname' => $input['firstname'],
            ':lastname' => $input['lastname'],
            ':email' => $input['email'],
            ':telephone' => $input['telephone'] ?? null,
            ':company' => $input['company'] ?? null,
            ':type' => $input['type'],
            ':assigned_to' => $input['assigned_to'],
            ':created_by' => $_SESSION['id']
        ]);

        http_response_code(201);
        echo json_encode(['message' => 'Contact successfully created.']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['message' => 'Failed to create contact: ' . $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['message' => 'Method not allowed.']);
}
?>
