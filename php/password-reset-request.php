<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve the email from the form
    $email = $_POST["email"];

    $db_host = "localhost";
    $db_username = "root";
    $db_password = "";
    $db_name = "Logi_Transport";

    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);


    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Execute the query to check if the email exists in the database
    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($found_email);
    $stmt->fetch();

    // Check if the email exists in the database
    if ($found_email) {
        // Email exists, store the email in a session variable and redirect to the password reset confirmation page
        session_start();
        $_SESSION['reset_email'] = $email;
        header("Location: ../password-reset-confirm.html");
        exit(); // Stop further execution of the script
    } else {
        // Email does not exist, display an error message
        echo "Error: Email does not exist in the database.";
    }

    $stmt->close();
    $conn->close();
}
?>
