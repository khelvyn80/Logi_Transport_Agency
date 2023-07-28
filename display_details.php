<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Journey Details - Logi Transport</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      margin: 0;
      background-color: #f2f2f2;
    }
    .container {
      text-align: center;
      background-color: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    h1 {
      color: #007bff;
    }
    p {
      color: #333;
      font-size: 18px;
    }
  </style>
</head>
<body>
  <div class="container">
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // Retrieve the form data
      $travelingFrom = $_POST['travelingFrom'];
      $travelingTo = $_POST['travelingTo'];
      $selectedDate = $_POST['selectedDate'];
    ?>
      <h1>Your Journey Details</h1>
      <p>Traveling From: <?php echo $travelingFrom; ?></p>
      <p>Traveling To: <?php echo $travelingTo; ?></p>
      <p>Selected Date: <?php echo $selectedDate; ?></p>
    <?php
    } else {
      // If the form is not submitted, display a message or redirect to the form page
      header("Location: index.html");
      exit();
    }
    ?>
  </div>
</body>
</html>
