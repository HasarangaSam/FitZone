<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
?>

<?php
include 'connection.php';

// Delete Class
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM fitness_programs WHERE id=$id");
    header("Location: admin_class.php");
}

// Fetch all classes
$result = $conn->query("SELECT * FROM fitness_programs");
?>

<!DOCTYPE html>
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
      <div class="nav-item active" onclick="window.location.href='admin_class.php'">
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

  <h2>Class List</h2>
  <br>
  <a href="add_class.php" class="addBtn">Add New Class</a>
    <table>
        <tr>
            <th>Class Name</th>
            <th>Description</th>
            <th>Specialities</th>
            <th>Image</th>
            <th>Action</th>
        </tr>

        <!-- Display each class details in a tabular format -->
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['program_name']; ?></td>
                <td><?php echo $row['description']; ?></td>
                <td><?php echo $row['specialties']; ?></td>
                <td><img src="<?php echo $row['image']; ?>" alt="Class Image" style="width:100px; height:auto;"></td>
                <td>
                    <a href="edit_class.php?id=<?php echo $row['id']; ?>" class="updateBtn">Update</a>
                    <a href="admin_class.php?delete=<?php echo $row['id']; ?>" class="deleteBtn"  onclick="return confirm('Are you sure want to delete?');">Delete</a>
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

