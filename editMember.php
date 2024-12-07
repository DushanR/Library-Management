<?php
// Include database configuration file
include 'config.php';

try {
    // Make sure the form data is being received
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data
        $memberId = $_POST['memberId'];  // Hidden input field for member ID
        $fullName = trim($_POST['fullName']);
        $nic = trim($_POST['nic']);
        $mobile = trim($_POST['mobile']);
        $email = trim($_POST['email']);  // Get email input

        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email format. Please check the email address.');
        }

        // Check connection
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }

        // Check if NIC already exists for another user (excluding current user)
        $checkNicQuery = "SELECT * FROM users WHERE nic = ? AND id != ?";
        $stmt = $conn->prepare($checkNicQuery);
        $stmt->bind_param("si", $nic, $memberId);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // NIC already exists for another user, show an error message
            throw new Exception('The NIC number already exists. Please use a different NIC.');
        } else {
            // Prepare the update query to edit the member details
            $updateQuery = "UPDATE users SET fullName = ?, nic = ?, mobile  = ? WHERE id = ?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("sssi", $fullName, $nic, $mobile, $memberId);
            $stmt->execute();

            // Close the connection
            $stmt->close();
            $conn->close();

            // Redirect to the adminManageUsers.html with a success flag in the URL
            header("Location: adminManageUsers.html?success2=true");
            exit;
        }
    } else {
        throw new Exception("Invalid request method.");
    }
} catch (Exception $e) {
    // Redirect to error page with error message
    header("Location: error.html");
    exit;
}
?>
