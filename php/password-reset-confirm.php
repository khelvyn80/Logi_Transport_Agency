<?php
session_start(); // Start the session

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newPassword = $_POST['new_password'];

    if (strlen($newPassword) < 8) {
        echo "Password must be at least 8 characters long.";
    } elseif (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $newPassword)) {
        echo "Password must contain at least one uppercase letter, one lowercase letter, one digit, and one special character.";
    } else {
        // Password reset is successful, update the user's password in the database
        // Retrieve the user's email from the session variable
        $email = $_SESSION['reset_email'];

        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

        $db_host = "localhost";
        $db_username = "root";
        $db_password = "";
        $db_name = "Logi_Transport";

        $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

       
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Update the user's password in the database
        $updatePasswordQuery = "UPDATE users SET password = ? WHERE email = ?";
        $stmt = $conn->prepare($updatePasswordQuery);
        $stmt->bind_param("ss", $hashedPassword, $email);

        if ($stmt->execute()) {
            // Password update successful, display a success message
            echo "Password reset successful. You can now log in with your new password.";

            // Redirect the user to the login page after a short delay (3 seconds)
            header("refresh:3; url=../login.html");
            exit();
        } else {
            echo "Error updating password: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    }
}
?>
