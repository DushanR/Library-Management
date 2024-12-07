<?php
// Include database configuration file
include 'config.php';

// Make sure the form data is being received
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $fullName = trim($_POST['fullName']);
    $nic = trim($_POST['nic']);
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } else {
        // Check if NIC already exists
        $checkNicQuery = "SELECT * FROM users WHERE nic = ?";
        $stmt = $conn->prepare($checkNicQuery);
        $stmt->bind_param("s", $nic);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // NIC already exists, show an error message
            echo "<script type='text/javascript'>
                    alert('The NIC number already exists. Please use a different NIC.');
                    window.history.back(); // Go back to the registration form
                  </script>";
        } else{
            $stmt = $conn->prepare("INSERT INTO users (fullName, nic, age, gender, address, mobile, email) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssissis", $fullName, $nic, $age, $gender, $address, $mobile, $email);
            $stmt->execute();

            // Close the connection
            $stmt->close();
            $conn->close();

            // Redirect to register.html with a success flag in the URL
            header("Location: adminManageUsers.html?success=true");
            exit;
        }
    }
} else {
    echo "Invalid request method.";
}
?>



