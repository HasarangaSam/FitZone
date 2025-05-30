<?php
// Start the session to keep track of logged-in users.
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: login.html"); // Redirect to login if not authenticated
  exit();
}

// Include the database connection file.
include 'connection.php';

// Fetch all customers and available classes for the dropdowns
$usersResult = $conn->query("SELECT id, fname, lname FROM users where userType='Customer'"); // Query to fetch user IDs and names.
$classesResult = $conn->query("SELECT id, program_name FROM fitness_programs"); // Query to fetch class IDs and names.

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_POST['user_id']; // Get the selected user ID from the form.
    $classId = $_POST['class_id']; // Get the selected class ID from the form.
    
    // Get the current month and year for the comparison
    $currentMonth = date('m'); // Current month 
    $currentYear = date('Y');  // Current year 
    
    // Check if the user has already booked the same class in the current month
    $checkSql = "
        SELECT * 
        FROM class_registration 
        WHERE user_id = ? 
        AND class_id = ? 
        AND MONTH(registration_date) = ? 
        AND YEAR(registration_date) = ?
    ";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("iiii", $userId, $classId, $currentMonth, $currentYear);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        // User already booked this class in the current month
        echo "<script>
                window.alert('User has already booked this class for the current month.');
                window.location.href = 'admin_booked_classes.php';
              </script>";
    } else {
        // Proceed to insert the registration
        $stmt = $conn->prepare("INSERT INTO class_registration (user_id, class_id, registration_date) VALUES (?, ?, NOW())");
        $stmt->bind_param("ii", $userId, $classId);  // Bind the user ID and class ID to the statement.

        if ($stmt->execute()) {
            echo "<script>
                    window.alert('New class successfully booked!');
                    window.location.href = 'admin_booked_classes.php';
                  </script>";
        } else {
            echo "<script>
                    window.alert('Error: Please try again.');
                    window.location.href = 'add_booked_class.php';
                  </script>";
        }
        $stmt->close();
    }

    $checkStmt->close();
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
      <div class="nav-item active" onclick="window.location.href='admin_booked_classes.php'">
        <img src="images/treadmill.png" class="nav-icon" alt="Booked Classes">
        <h3>Booked Classes</h3>
      </div>
      <div class="nav-item" onclick="window.location.href='admin_appointments.php'">
        <img src="images/meeting.png" class="nav-icon" alt="Booked Appointments">
        <h3>Appointments</h3>
      </div>
      <div class="nav-item" onclick="window.location.href='admin_booked_trainings.php'">
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
    <h2>Add Booked Class</h2>
    <form action="" method="POST">
        <label for="user">Select User:</label>
        <select name="user_id" required>
            <option value="">Select a customer</option>  <!-- Default option prompting user to select. -->
            <!-- Loop through fetched users to populate dropdown. -->
            <?php while ($row = $usersResult->fetch_assoc()): ?>   
                <!-- User options with names. -->
                <option value="<?php echo $row['id']; ?>"><?php echo $row['fname'] . ' ' . $row['lname']; ?></option> 
            <?php endwhile; ?>
        </select>

        <label for="class_id">Select Class:</label>
        <select name="class_id" required>
            <option value="">Select a class</option>
            <!-- Loop through fetched classes to populate dropdown. -->
            <?php while ($row = $classesResult->fetch_assoc()): ?>
              <!-- Class options with names. -->
                <option value="<?php echo $row['id']; ?>"><?php echo $row['program_name']; ?></option>
            <?php endwhile; ?>
        </select>

        <button type="submit" class="submit-button">Submit</button>
        <button type="button" class="back-button" onclick="window.location.href='admin_booked_classes.php'">Back</button>
    </form>
  </div>
</div>

</body>
</html>

<!-- close the databse connection -->
<?php
$conn->close(); 
?>
