<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if the user is logged in (i.e., the session variables are set)
    if (!isset($_SESSION['user_id'])) {
        echo "You must be logged in to submit the journey details.";
    } else {
        // Retrieve the form data
        $user_id = $_SESSION['user_id'];
        $travelingFrom = $_POST['travelingFrom'];
        $travelingTo = $_POST['travelingTo'];
        $selectedDate = $_POST['selectedDate'];

        $db_host = "localhost";
        $db_username = "root";
        $db_password = "";
        $db_name = "Logi_Transport";

        $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        //Eexecute the SQL query to insert journey plan details
        $stmt = $conn->prepare("INSERT INTO journey_plans (user_id, traveling_from, traveling_to, selected_date) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $user_id, $travelingFrom, $travelingTo, $selectedDate);

        if ($stmt->execute()) {
            // Journey plan details stored successfully
            echo "Journey details submitted successfully.";
        } else {
            echo "Error: " . $conn->error;
        }

        $stmt->close();
        $conn->close();
    }
}
?>
