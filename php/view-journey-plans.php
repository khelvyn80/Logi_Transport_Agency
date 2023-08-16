<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if the user is logged in (i.e., the session variables are set)
    if (!isset($_SESSION['user_id'])) {
        echo "You must be logged in to view journey plans.";
    } else {
        // Retrieve the user_id from the session
        $user_id = $_SESSION['user_id'];

        $db_host = "localhost";
        $db_username = "root";
        $db_password = "";
        $db_name = "Logi_Transport";

        $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Execute the SQL query to retrieve user credentials
        $stmt_user = $conn->prepare("
            SELECT username, email, contact
            FROM users
            WHERE id = ?
        ");
        $stmt_user->bind_param("i", $user_id);
        $stmt_user->execute();
        $result_user = $stmt_user->get_result();

        // Fetch user's credentials
        if ($result_user->num_rows > 0) {
            $user = $result_user->fetch_assoc();
            $username = $user['username'];
            $email = $user['email'];
            $contact = $user['contact'];
        }

        // Execute the SQL query to retrieve journey plan details
        $stmt_journey = $conn->prepare("
            SELECT traveling_from, traveling_to, selected_date
            FROM journey_plans
            WHERE user_id = ?
        ");
        $stmt_journey->bind_param("i", $user_id);
        $stmt_journey->execute();
        $result_journey = $stmt_journey->get_result();

        // Fetch journey plans if available
        $journey_plans = [];
        if ($result_journey->num_rows > 0) {
            while ($row = $result_journey->fetch_assoc()) {
                $journey_plans[] = $row;
            }
        }

        $stmt_user->close();
        $stmt_journey->close();
        $conn->close();
    }
}
?>

<!-- HTML content with the receipt and journey plan display -->
<!DOCTYPE html>
<html>
<head>
    <title>Journey Plans</title>
    <link rel="stylesheet" type="text/css" href="../styles/view-journey-plans.css">
</head>
<body>
    <div class="receipt">
        <img src="../styles/images/logo.png" alt="Logo" class="logo">
        <h1>Logi Transport</h1>
        <?php if (!empty($journey_plans)) { ?>
            <?php
            // Display user's credentials if journey plans are found
            echo "<h2>Journey Plans for " . $username . "</h2>";
            echo "<p>Email: " . $email . "</p>";
            echo "<p>Contact: " . $contact . "</p>";
            ?>
            <table>
                <tr><th>Traveling From</th><th>Traveling To</th><th>Selected Date</th></tr>
                <?php
                // Display journey plans in the table
                foreach ($journey_plans as $journey) {
                    echo "<tr>";
                    echo "<td>" . $journey['traveling_from'] . "</td>";
                    echo "<td>" . $journey['traveling_to'] . "</td>";
                    echo "<td>" . $journey['selected_date'] . "</td>";
                    echo "</tr>";
                }
                ?>
            </table>
        <?php } else {
            echo "<p>No journey plans found.</p>";
        } ?>
    </div>
</body>
</html>
