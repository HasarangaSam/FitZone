<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: login.html"); // Redirect to login if not authenticated
  exit();
}

include 'connection.php';
// Fetch Customers from users table
$result = $conn->query("SELECT * FROM users where userType='Customer'");
// Fetch admin and staff from users table
$result1 = $conn->query("SELECT * FROM users where userType = 'Admin' OR userType = 'Staff'");

//Get serarch keyword from form
$searchKeyword = isset($_GET['search']) ? trim($_GET['search']) : '';

// Fetch customers based on search keyword or show all
if (!empty($searchKeyword)) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE userType = 'Customer' AND fname LIKE ?");
    $searchParam = "%" . $searchKeyword . "%";
    $stmt->bind_param("s", $searchParam);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM users WHERE userType = 'Customer'");
}

// Delete User
if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  $conn->query("DELETE FROM users WHERE id=$id");
  header("Location: staff_user.php");
  exit();
}
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
      <div class="nav-item active" onclick="window.location.href='staff_user.php'">
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
        <img src="images/teacher.png" class="nav-icon" alt="rainers">
        <h3>Personal Trainers</h3>
      </div>
      <div class="nav-item" onclick="window.location.href='staff_booked_classes.php'">
        <img src="images/treadmill.png" class="nav-icon" alt="Classes">
        <h3>Booked Classes</h3>
      </div>
      <div class="nav-item" onclick="window.location.href='staff_appointments.php'">
        <img src="images/meeting.png" class="nav-icon" alt="Appointments">
        <h3>Appointments</h3>
      </div>
      <div class="nav-item" onclick="window.location.href='staff_booked_trainings.php'">
        <img src="images/group-class.png" class="nav-icon" alt="Trainings">
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

    <h2>Customer List</h2>
    <form method="GET" action="staff_user.php" class="search-form">
            <input 
                type="text" 
                name="search" 
                placeholder="Search Customer" 
                value="<?php echo htmlspecialchars($searchKeyword); ?>" 
                class="search-input">
            <button type="submit" class="search-button">Search</button>
            <button 
                type="button" 
                class="clear-button" 
                onclick="window.location.href='staff_user.php'">Clear</button>
     </form>
    <a href="staff_add_user.php" class="addBtn">Add New Customer</a>
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
        <!-- Display each class details in a tabular format -->
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['fname']; ?></td>
                <td><?php echo $row['lname']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['phone']; ?></td>
                <td><?php echo $row['gender']; ?></td>
                <td><?php echo $row['userType']; ?></td>
                <td>
                    <a href="staff_edit_user.php?id=<?php echo $row['id']; ?>" class="updateBtn">Update</a>
                    <a href="staff_user.php?delete=<?php echo $row['id']; ?>" class="deleteBtn" onclick="return confirm('Are you sure want to delete?');">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
    <br> <br>  
    <h2>Admin and Staff user List</h2>
    <table>
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Contact No</th>
            <th>Gender</th>
            <th>User Type</th>
        </tr>
        <!-- Display each class details in a tabular format -->
        <?php while ($row = $result1->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['fname']; ?></td>
                <td><?php echo $row['lname']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['phone']; ?></td>
                <td><?php echo $row['gender']; ?></td>
                <td><?php echo $row['userType']; ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
  </div>
</div>
</body>
</html>