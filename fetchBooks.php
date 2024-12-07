<?php
// Include database configuration file
include 'config.php';

// Query to fetch books
$sql = "SELECT book_id, coverImage, title, author, category, isbn, total_quantity, available_quantity FROM books";
$result = $conn->query($sql);

$books = [];
if ($result->num_rows > 0) {
    // Fetch all books
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }
}

// Close connection
$conn->close();

// Return data as JSON
echo json_encode($books);
?>
