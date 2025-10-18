<?php
$host = "localhost";   
$user = "root";        
$pass = "";            
$dbname = "db_lifelink"; // change to your database

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
