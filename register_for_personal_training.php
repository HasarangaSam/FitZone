<?php
include('connection.php');
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
?>

<?php

$user_id = $_SESSION['user_id'];

// Check if a trainer is selected through GET request
if (isset($_GET['trainer_id'])) {
    $trainer_id = $_GET['trainer_id'];

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['booking_date'])) {
        $booking_date = $_POST['booking_date'];

        // Check if the booking already exists for the selected date
        $check_sql = "SELECT * FROM pending_training WHERE user_id = ? AND trainer_id = ? AND booking_date = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param('iis', $user_id, $trainer_id, $booking_date);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            // Booking already exists, show error
            echo "<script>alert('You have already booked training with this trainer on the selected date.'); window.location.href='services.php';</script>";
        } else {
            // Insert the new booking into the "pending_training" database table
            $insert_sql = "INSERT INTO pending_training (user_id, trainer_id, booking_date) VALUES (?, ?, ?)";
            if ($insert_stmt = $conn->prepare($insert_sql)) {
                $insert_stmt->bind_param('iis', $user_id, $trainer_id, $booking_date);
                
                if ($insert_stmt->execute()) {
                    echo "<script>alert('Booking request submitted successfully! Waiting for approval.'); window.location.href='training.php';</script>";
                } else {
                    echo "<script>alert('There was an error submitting the booking request: " . $conn->error . "'); window.location.href='training.php';</script>";
                }
                $insert_stmt->close();
            } else {
                echo "<p>There was an error: " . $conn->error . "</p>";
            }
        }
        $check_stmt->close();
    }
} else {
    echo "<p>No trainer selected for booking.</p>";
    exit();
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Training Booking</title>
    <link rel="stylesheet" href="style.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="images/logo.jpg">
</head>

<body>
<header class="header">
    <nav class="navigation">
      <img src="images/logo.jpg" alt="Logo" class="logo">
      <ul>
          <li><a href="home.php">Home</a></li>
          <li><a href="about.php">About</a></li>
          <li><a href="membership.php">Memberships</a></li>
          <li><a href="classes.php">Classes</a></li>
          <li><a href="training.php"  class="active">Personal Training</a></li>
          <li><a href="blog.php">Blog</a></li>
          <?php if ($isLoggedIn): ?>
              <li><a href="contact.php">Contact</a></li>
          <?php else: ?>
              <li><a href="signup.html">Contact</a></li>
          <?php endif; ?>
          <?php if ($isLoggedIn): ?>
              <li><a href="customer.php">My Account</a></li>
          <?php else: ?>
              <li><a href="signup.html">My Account</a></li>
          <?php endif; ?>
          <?php if ($isLoggedIn): ?>
              <li><a href="logout.php" class="register-link">Logout</a></li>
          <?php else: ?>
              <li><a href="signup.html" class="register-link">Sign up</a></li>
          <?php endif; ?>
      </ul>
    </nav>
  </header>

    <main>
        <section class="booking-training">
            <h2>Request Training Booking</h2>
            <?php if (isset($trainer_id)) : ?>
                <form method="post" action="">
                    <label for="booking_date">Choose your preferred training date:</label>
                    <input type="date" name="booking_date" id="booking_date" required><br><br>
                    <button type="submit">Request Booking</button>
                </form>
            <?php endif; ?>
        </section>
    </main>
    <?php include 'footer.php'; ?>       
</body>
</html>
