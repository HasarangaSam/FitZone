<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.html"); // Redirect to login if not authenticated
  exit();
}

include 'connection.php';

// Fetch all data from customer_queries table
$result = $conn->query("SELECT * from customer_queries");

// Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM customer_queries WHERE id=$id");
    header("Location: staff_queries.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Staff Dashboard</title>
    <link rel="stylesheet" href="dashboardstyle.css">
    <link rel="icon" type="image/x-icon" href="images/logo.jpg">
</head>
<body>
<header>
  <div class="logo-section">
    <div class="logo">Staff Dashboard - FitZone Fitness Center</div>
  </div>
  <div class="header-icons">
    <div class="profile">
      <img src="images/staff.png" class="profile-icon" alt="profile">
    </div>
  </div>
</header>

<div class="container">
  <aside class="sidebar">
    <nav>
      <div class="nav-item" onclick="window.location.href='staff_user.php'">
        <img src="images/team.png" class="nav-icon" alt="Users">
        <h3>Users</h3>
      </div>
      <div class="nav-item" onclick="window.location.href='staff_membership.php'">
        <img src="images/member.png" class="nav-icon" alt="Memberships">
        <h3>Memberships</h3>
      </div>
      <div class="nav-item" onclick="window.location.href='staff_class.php'">
        <img src="images/class.png" class="nav-icon" alt="Classes">
        <h3>Classes</h3>
      </div>
      <div class="nav-item" onclick="window.location.href='staff_trainer.php'">
        <img src="images/teacher.png" class="nav-icon" alt="Trainers">
        <h3>Trainers</h3>
      </div>
      <div class="nav-item" onclick="window.location.href='staff_booked_classes.php'">
        <img src="images/treadmill.png" class="nav-icon" alt="Booked Classes">
        <h3>Booked Classes</h3>
      </div>
      <div class="nav-item" onclick="window.location.href='staff_appointments.php'">
        <img src="images/meeting.png" class="nav-icon" alt="Booked Appointments">
        <h3>Appointments</h3>
      </div>
      <div class="nav-item" onclick="window.location.href='staff_booked_trainings.php'">
        <img src="images/group-class.png" class="nav-icon" alt="Booked Trainings">
        <h3>Booked Trainings</h3>
      </div>
      <div class="nav-item active" onclick="window.location.href='staff_queries.php'">
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
        <h2>Customer Queries</h2>
        <table>
            <tr>
                <th>Full name</th>
                <th>Email</th>
                <th>Phone No</th>
                <th>Message</th>
                <th>Date</th>
                <th>Respond</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['full_name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['mobile']; ?></td>
                    <td><?php echo $row['message']; ?></td>
                    <td><?php echo $row['created_at']; ?></td>
                    <td><?php echo $row['respond']; ?></td>
                    <td>
                    <a href="staff_respond_query.php?id=<?php echo $row['id']; ?>" class="updateBtn">Respond</a>
                    <a href="staff_queries.php?delete=<?php echo $row['id']; ?>" class="deleteBtn"  onclick="return confirm('Are you sure want to delete?');">Delete</a>
                </td>
                </tr>
            <?php endwhile; ?>
        </table>
        <br>
            </div>
    </div>
</div>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>