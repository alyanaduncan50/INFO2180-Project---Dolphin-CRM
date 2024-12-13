<?php
    $servername = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = 'dolphin_crm';

    // Create connection
    $mysqli =  new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST'){


        $email = filter_input(INPUT_POST,'email', FILTER_SANITIZE_EMAIL);
        $password = filter_input(INPUT_POST,'password', FILTER_SANITIZE_STRING);
        $hashedPassword = '';
        $id = 0;
        
        if(!isset($_POST['email'])){
            die('email invalid');
        }

        if(empty($password)){
            die('password invalid');
        }

        $stmt = $mysqli->prepare("SELECT id, password FROM users WHERE email = ?");
        $stmt->bind_param('s',$email);
        $stmt->execute();
        $stmt->store_result();

        if($stmt->num_rows > 0){
            $stmt->bind_result($id, $hashedPassword);
            $stmt->fetch();
        }

        if(password_verify($password,$hashedPassword)){
            session_start(); // Start the session
            $_SESSION['email'] = $email;   

            $stmt = $mysqli->prepare("SELECT id FROM contacts WHERE email = ?");
            $stmt->bind_param('s',$email);
            $stmt->execute();
            $stmt->store_result();

            if($stmt->num_rows > 0){
                $stmt->bind_result($id);
                $stmt->fetch();
            }
            $_SESSION['id'] = $id;
            header('Location: ../dashboard.html');
            exit;
        }else{
            header('Location: ../login.html');
            exit;
        }
        
    } else {
        header('Location: ../login.html');
        exit; 
    }

?>