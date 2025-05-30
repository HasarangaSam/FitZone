<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.html"); // Redirect to login if not authenticated
  exit();
}

include 'connection.php';

// Delete 
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM personal_training WHERE id=$id");
    header("Location: admin_trainer.php");
}

// Fetch all personal trainers
$result = $conn->query("SELECT * FROM personal_training");
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
      <div class="nav-item" onclick="window.location.href='admin_class.php'">
        <img src="images/class.png" class="nav-icon" alt="Manage Classes">
        <h3>Manage Classes</h3>
      </div>
      <div class="nav-item active" onclick="window.location.href='admin_trainer.php'">
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

  <h2>Personal Trainer List</h2>
  <br>
  <a href="add_trainer.php" class="addBtn">Add New Trainer</a>
    <table>
        <tr>
            <th>Trainer Name</th>
            <th>Description</th>
            <th>Specialities</th>
            <th>Price</th>
            <th>Image</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['trainer_name']; ?></td>
                <td><?php echo $row['description']; ?></td>
                <td><?php echo $row['specialties']; ?></td>
                <td>Rs.<?php echo $row['price']; ?></td>
                <td><img src="<?php echo $row['image']; ?>" alt="Class Image" style="width:100px; height:auto;"></td>
                <td>
                    <a href="edit_trainer.php?id=<?php echo $row['id']; ?>" class="updateBtn">Update</a>
                    <a href="admin_trainer.php?delete=<?php echo $row['id']; ?>" class="deleteBtn"  onclick="return confirm('Are you sure want to delete?');">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
    <br>  
  </div>
</div>

</body>
</html>