<?php
// Start the session to track user login status.
session_start();

include('connection.php');

// Check if the user is logged in by checking if 'user_id' exists in the session.
$isLoggedIn = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Personal Trainings</title>
  <link rel="icon" type="image/x-icon" href="images/logo.jpg">
  <link rel="stylesheet" href="style.css"> 
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="icon" type="image/x-icon" href="images/logo.jpg">
  <script> 

        //Function to confirm to redirecting to login
        function confirmLogin() {
            if (confirm("You need to login before registering. Do you want to proceed to the login page?")) {
                window.location.href = 'login.html';
            }
        }
        // Function to confirm registration for personal training.
        function confirmRegistration(trainerID, trainerName) {
            if (confirm("Do you want to register for training with " + trainerName + "?")) {
                // Redirect to the registration page with the trainerId
                window.location.href = 'register_for_personal_training.php?trainer_id=' + trainerID;
            }
        }
  </script>
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
          <li><a href="training.php" class="active">Personal Training</a></li>
          <li><a href="blog.php">Blog</a></li>
          <li><a href="contact.php">Contact Us</a></li>
          <?php if ($isLoggedIn): ?>
            <?php
                $myAccountUrl = 'customer.php'; // Default My Account URL
                if (isset($_SESSION['user_type'])) {
                    switch ($_SESSION['user_type']) {
                        case 'Admin':
                            $myAccountUrl = 'admin_user.php';
                            break;
                        case 'Staff':
                            $myAccountUrl = 'staff_user.php';
                            break;
                        case 'Customer':
                            $myAccountUrl = 'customer.php';
                            break;
                        default:
                            $myAccountUrl = 'customer.php';
                            break;
                    }
                }
                ?>
                <li><a href="<?php echo $myAccountUrl; ?>">My Account</a></li>
          <?php else: ?>
              <li><a href="login.html">My Account</a></li>
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

<?php
$sql = "SELECT * FROM personal_training";
$result = $conn->query($sql);
?>

<section class="class-section">
    <div class="container">
        <h2 class="section-title">Personal Training with our Certified Trainers</h2>
        <div class="class-cards">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="class-card">
                        <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['trainer_name']) ?>">
                        <div class="card-content">
                            <h3><?= htmlspecialchars($row['trainer_name']) ?></h3>
                            <p><?= htmlspecialchars($row['description']) ?></p>
                            <p><strong>Specialties:</strong> <?= htmlspecialchars($row['specialties']) ?></p>
                            <p><strong>Price:</strong> Rs.<?= number_format($row['price'], 2) ?> per session</p>
                            
                            <?php if ($isLoggedIn): ?>
                                <button class="btn-register" onclick="confirmRegistration(<?= $row['id'] ?>, '<?= addslashes($row['trainer_name']) ?>')">Book Appointment</button>
                            <?php else: ?>
                                <button class="btn-register" onclick="confirmLogin()">Book Appointment</button>
                            <?php endif; ?>
                        </div> <!-- Close card-content -->
                    </div> <!-- Close class-card -->
                <?php endwhile; ?>
            <?php else: ?>
                <p>No trainers found.</p>
            <?php endif; ?>
        </div> <!-- Close class-cards -->
    </div> <!-- Close container -->
</section>

</main> 

<?php include 'footer.php'; ?>

</body>
</html>

<?php
$conn->close(); 
?>
