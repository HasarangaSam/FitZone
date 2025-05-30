<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);

include 'connection.php';

// Fetch users and trainers for the dropdowns
$usersResult = $conn->query("SELECT id, fname, lname FROM users");
$trainersResult = $conn->query("SELECT id, trainer_name FROM personal_training");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_POST['user_id'];
    $trainerId = $_POST['trainer_id'];
    $bookingDate = $_POST['booking_date'];

    // Insert into personal_training_registration table
    $stmt = $conn->prepare("INSERT INTO personal_training_registration (user_id, trainer_id, registration_date, booking_date) VALUES (?, ?, NOW(),?)");
    $stmt->bind_param("iis", $userId, $trainerId,$bookingDate);

    if ($stmt->execute()) {
      echo "<script>
      window.alert('New personal training session successfully booked!');
      window.location.href = 'admin_booked_trainings.php';
      </script>";
        exit;
    } else {
        echo "Error adding record: " . $conn->error;
    }
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - FitZone Fitness Center</title>
    <link rel="icon" type="image/x-icon" href="images/logo.jpg">
    <link rel="stylesheet" href="dashboardstyle.css">
</head>
<body>

<header>
  <div class="logo-section">
    <div class="logo">Admin Dashboard - FitZone Fitness Center</div>
  </div>
  <div class="header-icons">
    <div class="profile">
      <img src="images/profile.png" class="profile-icon" alt="profile">
    </div>
  </div>
</header>

<div class="container">
  <aside class="sidebar">
    <nav>
      <div class="nav-item" onclick="window.location.href='admin_user.php'">
        <img src="images/team.png" class="nav-icon" alt="Manage Users">
        <h3>Manage Users</h3>
      </div>
      <div class="nav-item" onclick="window.location.href='admin_membership.php'">
        <img src="images/member.png" class="nav-icon" alt="Manage Memberships">
        <h3>Manage Memberships</h3>
      </div>
      <div class="nav-item" onclick="window.location.href='admin_class.php'">
        <img src="images/class.png" class="nav-icon" alt="Manage Classes">
        <h3>Manage Classes</h3>
      </div>
      <div class="nav-item" onclick="window.location.href='admin_trainer.php'">
        <img src="images/teacher.png" class="nav-icon" alt="Manage Trainers">
        <h3>Manage Trainers</h3>
      </div>
      <div class="nav-item" onclick="window.location.href='admin_booked_classes.php'">
        <img src="images/treadmill.png" class="nav-icon" alt="Booked Classes">
        <h3>Booked Classes</h3>
      </div>
      <div class="nav-item" onclick="window.location.href='admin_appointments.php'">
        <img src="images/meeting.png" class="nav-icon" alt="Booked Appointments">
        <h3>Appointments</h3>
      </div>
      <div class="nav-item active" onclick="window.location.href='admin_booked_trainings.php'">
        <img src="images/group-class.png" class="nav-icon" alt="Booked Trainings">
        <h3>Booked Trainings</h3>
      </div>
      <div class="nav-item" onclick="window.location.href='admin_queries.php'">
        <img src="images/contact.png" class="nav-icon" alt="Queries">
        <h3>Queries</h3>
      </div>
      <div class="nav-item" onclick="window.location.href='logout.php'">
        <img src="images/logout.png" class="nav-icon" alt="Logout">
        <h3>Logout</h3>
      </div>
    </nav>
  </aside>

  <div class="main-content">
    <h2>Add New Personal Training Session</h2>
    <form action="add_booked_training.php" method="POST">
        <label for="user">Select User:</label>
        <select name="user_id" required>
            <option value="">Select a user</option>
            <?php while ($row = $usersResult->fetch_assoc()): ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['fname'] . ' ' . $row['lname']; ?></option>
            <?php endwhile; ?>
        </select>

        <label for="trainer">Select Trainer:</label>
        <select name="trainer_id" required>
            <option value="">Select a trainer</option>
            <?php while ($row = $trainersResult->fetch_assoc()): ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['trainer_name']; ?></option>
            <?php endwhile; ?>
        </select>

        <label for="booking_date">Select Booking Date:</label>
        <input type="date" name="booking_date" required>

        <button type="submit" class="submit-button">Submit</button>
        <button type="button" class="back-button" onclick="window.location.href='admin_booked_trainings.php'">Back</button>
    </form>
  </div>
</div>

</body>
</html>

<?php
$conn->close(); 
?>
