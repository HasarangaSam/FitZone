<?php
session_start();

include 'connection.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.html"); // Redirect to login if not authenticated
  exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $conn->query("SELECT * FROM customer_queries WHERE id=$id");
    $query = $result->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $response = $_POST['response'];

    $stmt = $conn->prepare("UPDATE customer_queries SET respond=? WHERE id=?");
    $stmt->bind_param("si", $response, $id);

    if ($stmt->execute()) {
        echo "<script>
                window.alert('Response successfully updated!');
                window.location.href = 'admin_queries.php';
              </script>";
    } else {
        echo "<script>
                window.alert('Error: Could not update the response. Please try again.');
                window.location.href = 'respond_query.php?id=$id';
              </script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Respond to Query</title>
    <link rel="stylesheet" href="dashboardStyle.css">
    <link rel="icon" type="image/x-icon" href="images/logo.jpg">
    <script>
      function validateResponse() {
          const response = document.querySelector('textarea[name="response"]').value;
          if (response === "") {
              alert("Please provide a response before submitting.");
              return false;
          }
          return true;
      }
    </script>
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
      <div class="nav-item" onclick="window.location.href='admin_booked_trainings.php'">
        <img src="images/group-class.png" class="nav-icon" alt="Booked Trainings">
        <h3>Booked Trainings</h3>
      </div>
      <div class="nav-item active" onclick="window.location.href='admin_queries.php'">
        <img src="images/contact.png" class="nav-icon" alt="Queries">
        <h3>Queries</h3>
      </div>
      <div class="nav-item" onclick="window.location.href='admin_logout.php'">
        <img src="images/logout.png" class="nav-icon" alt="Logout">
        <h3>Logout</h3>
      </div>
    </nav>
  </aside>

    <div class="main-content">
        <h2>Respond to Customer Query</h2>
        <form action="respond_query.php" method="POST" onsubmit="return validateResponse()">
            <input type="hidden" name="id" value="<?php echo $query['id']; ?>">

            <label>Full Name:</label>
            <input type="text" name="full_name" value="<?php echo $query['full_name']; ?>" disabled><br>

            <label>Email:</label>
            <input type="email" name="email" value="<?php echo $query['email']; ?>" disabled><br>

            <label>Phone No:</label>
            <input type="text" name="phone_no" value="<?php echo $query['mobile']; ?>" disabled><br>

            <label>Message:</label>
            <textarea name="message" disabled><?php echo $query['message']; ?></textarea><br>

            <label>Response:</label>
            <textarea name="response"><?php echo $query['respond']; ?></textarea><br>

            <button type="submit" class="submit-button">Submit</button>
            <button type="button" class="back-button" onclick="window.location.href='admin_queries.php'">Back</button>
        </form>
    </div>
</div>

</body>
</html>

