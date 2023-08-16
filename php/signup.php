<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if all required fields are filled
    if (empty($_POST["username"]) || empty($_POST["email"]) || empty($_POST["contact"]) || empty($_POST["password"]) || empty($_POST["confirmPassword"])) {
        echo "Please fill in all the required fields.";
    } else {
        $username = $_POST["username"];
        $email = $_POST["email"];
        $contact = $_POST["contact"];
        $password = $_POST["password"];
        $confirmPassword = $_POST["confirmPassword"];

        // Check if password is at least 8 characters long and contains at least one uppercase letter, one lowercase letter, and one digit
        if (strlen($password) < 8 || !preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/", $password)) {
            echo "Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, and one digit.";
        } elseif ($password !== $confirmPassword) {
            echo "Passwords do not match.";
        } else {
            $db_host = "localhost";
            $db_username = "root";
            $db_password = "";
            $db_name = "Logi_Transport";

            $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Check if the username and email are unique
            $stmt = $conn->prepare("SELECT username, email FROM users WHERE username = ? OR email = ?");
            $stmt->bind_param("ss", $username, $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                echo "Username or email already exists. Please choose a different one.";
            } else {
                // Prepare and execute the SQL query to insert user details
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (username, email, contact, password) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $username, $email, $contact, $hashed_password);

                if ($stmt->execute()) {
                    // Retrieve the newly created user's ID
                    $user_id = $conn->insert_id;

                    // Check if journey plan details are submitted
                    if (isset($_POST["travelingFrom"]) && isset($_POST["travelingTo"]) && isset($_POST["selectedDate"])) {
                        $travelingFrom = $_POST["travelingFrom"];
                        $travelingTo = $_POST["travelingTo"];
                        $selectedDate = $_POST["selectedDate"];

                        // Prepare and execute the SQL query to insert journey plan details
                        $stmt = $conn->prepare("INSERT INTO journey_plans (user_id, traveling_from, traveling_to, selected_date) VALUES (?, ?, ?, ?)");
                        $stmt->bind_param("isss", $user_id, $travelingFrom, $travelingTo, $selectedDate);

                        if ($stmt->execute()) {
                            // Redirect the user to login.html after successful sign-up
                            header("Location: ../login.html");
                            exit();
                        } else {
                            echo "Error: " . $stmt->error;
                        }
                    } else {
                        // Redirect the user to login.html after successful sign-up (without journey plan details)
                        header("Location: ../login.html");
                        exit();
                    }
                } else {
                    echo "Error: " . $stmt->error;
                }
            }

            $stmt->close();
            $conn->close();
        }
    }
}
?>
