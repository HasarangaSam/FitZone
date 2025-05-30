<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: login.html"); // Redirect to login if not authenticated
  exit();
}

// Include database connection 
include 'connection.php';

// Fetch Membership plans
$result = $conn->query("SELECT * FROM membership_plans");

//Fetch users who has memberships
$result2 = $conn->query("SELECT users.fname, users.lname, users.email, membership_plans.plan_name
    FROM users
    INNER JOIN membership_registration ON users.id = membership_registration.user_id
    INNER JOIN membership_plans ON membership_registration.membership_id = membership_plans.id
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Staff Dashboard - FitZone Fitness Center</title>
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
        <img src="images/team.png" class="nav-icon" alt="Manage Users">
        <h3>Users</h3>
      </div>
      <div class="nav-item active" onclick="window.location.href='staff_membership.php'">
        <img src="images/member.png" class="nav-icon" alt="Manage Memberships">
        <h3>Memberships</h3>
      </div>
      <div class="nav-item" onclick="window.location.href='staff_class.php'">
        <img src="images/class.png" class="nav-icon" alt="Manage Classes">
        <h3>Classes</h3>
      </div>
      <div class="nav-item" onclick="window.location.href='staff_trainer.php'">
        <img src="images/teacher.png" class="nav-icon" alt="Manage Trainers">
        <h3>Trainers</h3>
      </div>
      <div class="nav-item" onclick="window.location.href='staff_booked_classes.php'">
        <img src="images/treadmill.png" class="nav-icon" alt="Booked Classes">
        <h3>Classes</h3>
      </div>
      <div class="nav-item" onclick="window.location.href='staff_appointments.php'">
        <img src="images/meeting.png" class="nav-icon" alt="Booked Appointments">
        <h3>Appointments</h3>
      </div>
      <div class="nav-item" onclick="window.location.href='staff_booked_trainings.php'">
        <img src="images/group-class.png" class="nav-icon" alt="Booked Trainings">
        <h3>Booked Trainings</h3>
      </div>
      <div class="nav-item" onclick="window.location.href='staff_queries.php'">
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

  <h2>Membership List</h2>
    <table>
        <tr>
            <th>Membership Plan</th>
            <th>Description</th>
            <th>Benefits</th>
            <th>Features</th>
            <th>Price</th>
            <th>Promotions</th>
            <th>Image</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['plan_name']; ?></td>
                <td><?php echo $row['description']; ?></td>
                <td><?php echo $row['benefits']; ?></td>
                <td><?php echo $row['benefits']; ?></td>
                <td>Rs.<?php echo $row['price']; ?></td>
                <td><?php echo $row['promotions']; ?></td>
                <td><img src="<?php echo $row['image']; ?>" alt="membership Image" style="width:100px; height:auto;"></td>
            </tr>
        <?php endwhile; ?>
    </table>
    <br>  <br>

    <h2>Users who registered for a membership</h2>
    <table>
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Membership</th>
        </tr>
        <?php while ($row = $result2->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['fname']; ?></td>
                <td><?php echo $row['lname']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['plan_name']; ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
  </div>
</div>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>