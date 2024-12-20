<?php

$msg = "";


if(isset($_POST['submit'])){

    $host = "localhost";
    $dbname = "project";
    $username = "root";
    $password = "";

    $conn = mysqli_connect(hostname: $host, username: $username, password: $password, database: $dbname);

    $firstname = $conn->real_escape_string($_POST["fname"]);
    $lastname = $conn->real_escape_string($_POST["lname"]);
    $password1 = $conn->real_escape_string($_POST["password"]);
    $email = $conn->real_escape_string($_POST["email"]);
    $role = $conn->real_escape_string($_POST["role"]);
    $created_at = date("y-m-d h:i:s");

    $passhash = password_hash($password1,PASSWORD_DEFAULT);
    $conn->query(query:"INSERT INTO user (firstname,lastname,password,email,role,created_at) VALUES('$firstname','$lastname','$passhash','$email','$role','$created_at')");
    echo "User added!";
}