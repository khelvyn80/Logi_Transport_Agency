<?php
session_start(); // Start the session

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Establish database connection (you may need to adjust these settings)
    $db_host = "localhost";
    $db_username = "root";
    $db_password = "";
    $db_name = "Logi_Transport";

    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the SQL query to retrieve the user's hashed password and journey plan details
    $stmt = $conn->prepare("SELECT users.id, password, traveling_from, traveling_to, selected_date FROM users LEFT JOIN journey_plans ON users.id = journey_plans.user_id WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($user_id, $hashed_password, $travelingFrom, $travelingTo, $selectedDate);
    $stmt->fetch();

    // Verify the password
    if (password_verify($password, $hashed_password)) {
        // Password is correct, user logged in successfully
        // Store the journey plan details in session variables
        $_SESSION['user_id'] = $user_id;
        $_SESSION['travelingFrom'] = $travelingFrom;
        $_SESSION['travelingTo'] = $travelingTo;
        $_SESSION['selectedDate'] = $selectedDate;

        header("Location: ../index.html");
        exit(); // Make sure to stop the script execution after redirection
    } else {
        echo "Invalid email or password.";
    }

    $stmt->close();
    $conn->close();
}
?>
