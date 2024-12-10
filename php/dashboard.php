<?php
    session_start();

    // Check if the user is logged in
    if (!isset($_SESSION['email'])) {
        header("Location: ../login.html"); // Redirect to login page if not logged in
        exit();
    }

    $host = 'localhost';
    $user = 'root';
    $password = '';
    $dbname = 'dolphin_crm';

    $mysqli = new mysqli($host,$user,$password,$dbname);
    
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    $result= $mysqli->query("SELECT title, firstname, lastname, email, company, type FROM contacts");
    $data = array();
    
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    $result->free();
    $mysqli->close();

    // Serve the existing dashboard.html file
    
    echo json_encode([
       'data'=> $data
    ]);

?>
