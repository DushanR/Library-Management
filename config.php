<?php
// Database connection
$host = "localhost"; // Change as per your configuration
$user = "root"; // Change as per your configuration
$dbpassword = ""; // Change as per your configuration
$dbname = "library"; // Change as per your database name

$conn = new mysqli($host, $user, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>