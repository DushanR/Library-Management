<?php
// Include database configuration file
include 'config.php';

// Handle AJAX request to fetch user data
if (isset($_GET['userID'])) {
    $userID = $conn->real_escape_string($_GET['userID']);

    // SQL query to fetch member name, borrowed books, and due dates
    $query = "
        SELECT 
            u.fullName AS MemberName, 
            b.book_id AS BookID, 
            b.title AS BookTitle, 
            ib.Due_date AS DueDate
        FROM 
            issuebook ib
        JOIN 
            users u ON ib.User_ID = u.id
        JOIN 
            books b ON ib.Book_ID = b.book_id
        WHERE 
            u.id = $userID";

    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $response = ['status' => 'success', 'data' => []];

        while ($row = $result->fetch_assoc()) {
            $response['data'][] = $row;
        }

        echo json_encode($response);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No records found for this Member ID']);
    }

    exit;
}

// Handle form submission for returning a book
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userID = $conn->real_escape_string($_POST['returnUserID']);
    $bookData = json_decode($_POST['returnbooks'], true); // Get Book ID from dropdown
    $bookID = $bookData['BookID'];
    $dateReturned = $conn->real_escape_string($_POST['dateReturned']);

    // Remove the book from the `issuebook` table
    $deleteQuery = "DELETE FROM issuebook WHERE User_ID = $userID AND Book_ID = $bookID";
    if ($conn->query($deleteQuery) === TRUE) {
        // Increase `available_quantity` in the `books` table
        $updateQuery = "UPDATE books SET available_quantity = available_quantity + 1 WHERE book_id = $bookID";
        if ($conn->query($updateQuery) === TRUE) {
            header("Location: adminIssueBooks.html?success=true&message=Book%20returned%20successfully.");
        } else {
            header("Location: adminIssueBooks.html?success=true&message=Failed%20to%20update%20available%20quantity.");
        }
    } else {
        header("Location: adminIssueBooks.html?success=true&message=Failed%20to%20remove%20book%20from%20issue%20records.");
    }

    exit;
}

echo json_encode(['status' => 'error', 'message' => 'Invalid Request']);
$conn->close();
?>
