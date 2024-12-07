<?php
// Get the user ID from the POST request
$data = json_decode(file_get_contents('php://input'), true);
$userId = $data['id'];

// Database connection
$host = 'localhost'; // or your database host
$username = 'root';  // your MySQL username
$dbpassword = "";     // your MySQL password
$dbname = 'library'; // your database name

$conn = new mysqli($host, $username, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL to delete the user by ID
$sql = "DELETE FROM users WHERE id = ?";

// Prepare and bind
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);

// Execute the query
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to delete user']);
}

// Close the connection
$stmt->close();
$conn->close();
?>
