<?php
// Include database configuration file
include 'config.php';

// Set the mysqli connection to throw exceptions on errors
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Ensure logs directory and file exist
$logFile = 'logs/issueReturn.log';
if (!file_exists(dirname($logFile))) {
    mkdir(dirname($logFile), 0777, true);
}

// Function to log messages
function logMessage($message) {
    global $logFile;
    error_log(date('[Y-m-d H:i:s] ') . $message . PHP_EOL, 3, $logFile);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize the form data
    $userID = intval($_POST['userID']);
    $userName = htmlspecialchars($_POST['userName'], ENT_QUOTES, 'UTF-8');
    $bookID = intval($_POST['bookID']);
    $bookName = htmlspecialchars($_POST['bookName'], ENT_QUOTES, 'UTF-8');
    $dateBorrowed = $_POST['dateBorrowed']; // Ensure correct format (Y-m-d)
    $dueDate = $_POST['dueDate']; // Ensure correct format (Y-m-d)

    try {
        // Log the book issue attempt
        logMessage("Attempting to issue book with ID: $bookID to user with ID: $userID.");

        // Check how many books the user has already borrowed
        $checkStmt = $conn->prepare("SELECT COUNT(*) FROM issueBook WHERE User_ID = ?");
        $checkStmt->bind_param("i", $userID);
        $checkStmt->execute();
        $checkStmt->bind_result($borrowedBooksCount);
        $checkStmt->fetch();
        $checkStmt->close();

        // If the user has borrowed 3 or more books, deny the borrow request
        if ($borrowedBooksCount >= 3) {
            logMessage("User with ID: $userID has already borrowed 3 or more books. Denying borrow request.");
            header("Location: adminIssueBooks.html?success=false&message=Member%20can%20only%20borrow%20up%20to%203%20books.");
            exit;
        }

        // Check the available quantity of the book
        $qtyStmt = $conn->prepare("SELECT available_quantity FROM books WHERE book_id = ?");
        $qtyStmt->bind_param("i", $bookID);
        $qtyStmt->execute();
        $qtyStmt->bind_result($totalQuantity);
        $qtyStmt->fetch();
        $qtyStmt->close();

        // If the book quantity is 0, deny the borrow request
        if ($totalQuantity <= 0) {
            logMessage("Book with ID: $bookID is out of stock. Denying borrow request.");
            header("Location: adminIssueBooks.html?success=false&message=Sorry,%20this%20book%20is%20currently%20out%20of%20stock.");
            exit;
        }

        // Check if the same user has already borrowed this book
        $borrowCheckStmt = $conn->prepare("SELECT COUNT(*) FROM issueBook WHERE User_ID = ? AND Book_ID = ?");
        $borrowCheckStmt->bind_param("ii", $userID, $bookID);
        $borrowCheckStmt->execute();
        $borrowCheckStmt->bind_result($alreadyBorrowedCount);
        $borrowCheckStmt->fetch();
        $borrowCheckStmt->close();

        if ($alreadyBorrowedCount > 0) {
            logMessage("User with ID: $userID has already borrowed book with ID: $bookID. Denying borrow request.");
            header("Location: adminIssueBooks.html?success=false&message=This%20member%20already%20borrowed%20this%20book.");
            exit;
        }

        // Prepare and bind for inserting data into issueBook table
        $stmt = $conn->prepare("INSERT INTO issueBook (User_ID, User_Name, Book_ID, BookTitle, Date_borrowed, Due_date) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isisss", $userID, $userName, $bookID, $bookName, $dateBorrowed, $dueDate);

        // Execute the query
        if ($stmt->execute()) {
            // Prepare and bind for updating the book quantity in books table
            $updateStmt = $conn->prepare("UPDATE books SET available_quantity = available_quantity - 1 WHERE book_id = ?");
            $updateStmt->bind_param("i", $bookID);

            // Execute the update query
            if ($updateStmt->execute()) {
                logMessage("Successfully issued book with ID: $bookID to user with ID: $userID.");
                // Trigger the modal to show on success
                header("Location: adminIssueBooks.html?success=true&message=Book%20has%20been%20issued%20successfully.");
                exit;
            } else {
                throw new Exception("Error updating book quantity: " . $updateStmt->error);
            }

            // Close the update statement
            $updateStmt->close();
        } else {
            throw new Exception("Error executing issueBook query: " . $stmt->error);
        }

        // Close the insert statement
        $stmt->close();
    } catch (Exception $e) {
        logMessage("Exception occurred: " . $e->getMessage());
        // Redirect to the error page if an exception occurs
        header("Location: error.html?error=" . urlencode($e->getMessage()));
        exit;
    } finally {
        // Close the connection
        $conn->close();
    }
}
?>
