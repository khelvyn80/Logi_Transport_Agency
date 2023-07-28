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

            // Check if the username and email are unique
            $stmt = $conn->prepare("SELECT username, email FROM users WHERE username = ? OR email = ?");
            $stmt->bind_param("ss", $username, $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                echo "Username or email already exists. Please choose a different one.";
            } else {
                // Prepare and execute the SQL query
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (username, email, contact, password) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $username, $email, $contact, $hashed_password);

                if ($stmt->execute()) {
                    // Redirect the user to login.html after successful sign-up
                    header("Location: login.html");
                    exit(); // Make sure to stop the script execution after redirection
                } else {
                    echo "Error: " . $conn->error;
                }
            }

            $stmt->close();
            $conn->close();
        }
    }
}
?>