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

    $mysqli = new mysqli($host, $user, $password, $dbname);

    if ($mysqli->connect_error) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Database connection failed: ' . $mysqli->connect_error]);
        exit();
    }

    $filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

    $data = [];
    if ($filter === 'all') {
        $query = "SELECT id, title, firstname, lastname, email, company, type FROM contacts";
        $result = $mysqli->query($query);

        if (!$result) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Query failed: ' . $mysqli->error]);
            exit();
        }

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $result->free();

    }elseif($filter === 'Assigned to me'){
        if (!isset($_SESSION['id'])) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Session ID not set. Please log in.']);
            exit();
        }
        $userId = $_SESSION['id'];
        $query = $mysqli->prepare("SELECT id, title, firstname, lastname, email, company, type FROM contacts WHERE assigned_to = ?");
        
        if (!$query) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Prepared statement failed: ' . $mysqli->error]);
            exit();
        }
        $query->bind_param("s", $userId);
        $query->execute();
        $result = $query->get_result();

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $query->close();
        
    } else {
        $query = $mysqli->prepare("SELECT id, title, firstname, lastname, email, company, type FROM contacts WHERE type = ?");
        if (!$query) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Prepared statement failed: ' . $mysqli->error]);
            exit();
        }
        $query->bind_param("s", $filter);
        $query->execute();
        $result = $query->get_result();

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $query->close();
    }

    $mysqli->close();

    header('Content-Type: application/json');
    echo json_encode(['data' => $data], JSON_PRETTY_PRINT);
    exit();
?>
