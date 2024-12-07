<?php
// Include database configuration file
include 'config.php';

// Check if userID is provided
if (isset($_GET['bookID'])) {
    $bookID = $_GET['bookID'];

    // Query to get user details by userID
    $sql = "SELECT title FROM books WHERE book_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $bookID);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($title);
        $stmt->fetch();
        
        // Respond with success and fullname
        echo json_encode(['success' => true, 'title' => $title]);
    } else {
        // If no user is found with the given userID
        echo json_encode(['success' => false, 'message' => 'Book not found']);
    }
    
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'No bookID provided']);
}

$conn->close();
?>
