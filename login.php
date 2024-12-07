<?php
// Include database configuration file
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate input
    if (empty($username) || empty($password)) {
        header("Location: login.html?error=Username%20and%20password%20are%20required.");
        exit();
    }

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch the hashed password for the given username
    $query = "SELECT password FROM useradmin WHERE username = ?";
    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Username exists, fetch the stored hashed password
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashedPassword)) {
            // Successful login
            header("Location: dashboard.php");
            exit;
        } else {
            // Incorrect password, pass error through query string
            header("Location: login.html?error=Invalid%20Username%20or%20password.");
            exit();
        }
    } else {
        // Username does not exist, pass error through query string
        header("Location: login.html?error=Invalid%20Username%20or%20password.");
        exit();
    }

    // Close connection
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
