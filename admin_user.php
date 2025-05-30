<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: login.html"); // Redirect to login if not authenticated
  exit();
}

// Include database connection file
include 'connection.php';


// Delete User
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM users WHERE id=$id");
    header("Location: admin_user.php");
    exit();
}

// Fetch Users
$result = $conn->query("SELECT * FROM users");

// Handle search request
$searchKeyword = "";
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['search'])) {
    $searchKeyword = $_GET['search'];
}

// Query to fetch users based on the search keyword
$stmt = $conn->prepare("SELECT * FROM users WHERE fname LIKE CONCAT('%', ?, '%')");
$stmt->bind_param("s", $searchKeyword);
$stmt->execute();
$result = $stmt->get_result();
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
      <div class="nav-item active" onclick="window.location.href='admin_user.php'">
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

  <h2>User List</h2>
   <!-- Search Bar -->
  <form method="GET" action="admin_user.php" class="search-form">
            <input 
                type="text" 
                name="search" 
                placeholder="Search by First Name" 
                value="<?php echo htmlspecialchars($searchKeyword); ?>" 
                class="search-input">
            <button type="submit" class="search-button">Search</button>
            <button type="button" class="clear-button" onclick="window.location.href='admin_user.php'">Clear</button>
    <form>
    
    <a href="add_user.php" class="addBtn">Add New User</a> 
     
     <!-- User table -->
    <table>
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Contact No</th>
            <th>Gender</th>
            <th>User Type</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['fname']; ?></td>
                <td><?php echo $row['lname']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['phone']; ?></td>
                <td><?php echo $row['gender']; ?></td>
                <td><?php echo $row['userType']; ?></td>
                <td>
                    <a href="edit_user.php?id=<?php echo $row['id']; ?>" class="updateBtn">Update</a>
                    <a href="admin_user.php?delete=<?php echo $row['id']; ?>" class="deleteBtn" onclick="return confirm('Are you sure want to delete?');">Delete</a>
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
