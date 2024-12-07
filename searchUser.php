<?php
// Include database configuration file
include 'config.php';

// Check if userID is provided
if (isset($_GET['userID'])) {
    $userID = $_GET['userID'];

    // Query to get user details by userID
    $sql = "SELECT fullname FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($fullname);
        $stmt->fetch();
        
        // Respond with success and fullname
        echo json_encode(['success' => true, 'fullname' => $fullname]);
    } else {
        // If no user is found with the given userID
        echo json_encode(['success' => false, 'message' => 'User not found']);
    }
    
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'No userID provided']);
}
$conn->close();
?>
