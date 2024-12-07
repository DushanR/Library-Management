<?php
// Include database configuration file
include 'config.php';

// Query to fetch users
$sql = "SELECT id, fullName, nic, mobile, email FROM users";
$result = $conn->query($sql);

$users = [];
if ($result->num_rows > 0) {
    // Fetch all users
    while($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

// Close connection
$conn->close();

// Return data as JSON
echo json_encode($users);
?>
