<?php
// Include database configuration file
include 'config.php';

// Ensure the form data is being received
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Validate received data
    if (empty($username) || empty($password)) {
        header("Location: register.php?error=" . urlencode("Username or password cannot be empty."));
        exit();
    }

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } else {
        // Hash and salt the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO useradmin (username, password) VALUES (?, ?)");
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        $bind = $stmt->bind_param("ss", $username, $hashedPassword);
        if ($bind === false) {
            die("Bind failed: " . $stmt->error);
        }

        $exec = $stmt->execute();
        if ($exec === false) {
            die("Execute failed: " . $stmt->error);
        }

        // Close the connection
        $stmt->close();
        $conn->close();

        // Redirect to register.php with a success flag in the URL
        header("Location: register.php?success=true");
        exit();
    }
} else {
    echo "Invalid request method.";
}
?>
