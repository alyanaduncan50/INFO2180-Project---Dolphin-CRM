<?php
$servername = 'localhost';
$username = '';
$password = '';
$dbname = 'dolphin_crm';

// Create connection
$conn = mysqli_connect($servername, $username, $password,$dbname);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

print("connected");
?>