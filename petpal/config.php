<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "PetPal";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
