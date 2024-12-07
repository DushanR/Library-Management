<?php
// Include database configuration file
include 'config.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $title = $_POST['title'];
    $author = $_POST['author'];
    $category = $_POST['category'];
    $isbn = $_POST['isbn'];
    $total_quantity = $_POST['total_quantity'];
    $available_quantity = $_POST['total_quantity'];

    // Handle file upload
    $upload_dir = "uploads/"; // Directory to store uploaded files

    // Ensure upload directory exists
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true); // Create directory if it doesn't exist
    }

    $file_name = basename($_FILES["book_cover"]["name"]);
    $image_file_type = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $allowed_file_types = ['jpg', 'jpeg', 'png', 'gif'];

    // Check if file is a valid image
    if (!in_array($image_file_type, $allowed_file_types)) {
        die("Error: Only JPG, JPEG, PNG, and GIF files are allowed.");
    }

    // Sanitize the book title to create a valid filename
    $sanitized_title = preg_replace('/[^a-zA-Z0-9-_]/', '_', $title);
    $new_file_name = $sanitized_title . '.' . $image_file_type;
    $target_file = $upload_dir . $new_file_name;

    // Move uploaded file to the upload directory with the new filename
    if (!move_uploaded_file($_FILES["book_cover"]["tmp_name"], $target_file)) {
        die("Error uploading the file.");
    }

    // Insert data into the database
    $sql = "INSERT INTO books (coverImage, title, author, category, isbn, total_quantity, available_quantity) 
            VALUES ('$target_file', '$title', '$author', '$category', '$isbn', $total_quantity, $available_quantity)";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Book added successfully!'); window.location.href = 'adminManageBooks.html';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
