<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: login.html"); // Redirect to login if not authenticated
  exit();
}

include 'connection.php';

// Fetch user and class information using inner joins for displaying in the table
$result = $conn->query("SELECT class_registration.registration_id, class_registration.registration_date, fitness_programs.program_name, users.fname, users.lname, users.email
    FROM users
    INNER JOIN class_registration ON users.id = class_registration.user_id
    INNER JOIN fitness_programs ON class_registration.class_id = fitness_programs.id
    ORDER BY class_registration.registration_date DESC");

// Delete 
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM class_registration WHERE registration_id=$id");
    header("Location: admin_booked_classes.php"); // Redirect back to the booked classes page
  }

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - FitZone Fitness Center</title>
    <link rel="stylesheet" href="dashboardstyle.css">
    <link rel="icon" type="image/x-icon" href="images/logo.jpg">
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
        <h2>Booked Class List</h2>
        <a href="add_booked_class.php" class="addBtn">Add New Class Booking</a>
        <table>
            <tr>
                <th>Registered Date and Time</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Class</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>  <!-- Loop through each row in the result -->
                <tr>
                    <td><?php echo $row['registration_date']; ?></td>
                    <td><?php echo $row['fname']; ?></td>
                    <td><?php echo $row['lname']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['program_name']; ?></td>
                    <td>
                        <!-- Link to delete the booking with confirmation -->
                        <a href="admin_booked_classes.php?delete=<?php echo $row['registration_id']; ?>" class="deleteBtn" onclick="return confirm('Are you sure?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
        <br>
    </div>
</div>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
