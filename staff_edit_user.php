<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: login.html"); // Redirect to login if not authenticated
  exit();
}

include 'connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $conn->query("SELECT * FROM users WHERE id=$id") or die($conn->error);
    $user = $result->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $gender = $_POST['gender'];
    $password = $_POST['pw'];
    $usertype = $_POST['usertype'];

    // Check if the password is empty (to keep the old password if not provided)
    if (!empty($_POST['pw'])) {
        $password = password_hash($_POST['pw'], PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET fname=?, lname=?, email=?, phone=?, gender=?, password=?, usertype=? WHERE id=?");
        $stmt->bind_param("sssssssi", $fname, $lname, $email, $phone, $gender, $password, $usertype, $id);
    } else {
        // If password is not updated
        $stmt = $conn->prepare("UPDATE users SET fname=?, lname=?, email=?, phone=?, gender=?, usertype=? WHERE id=?");
        $stmt->bind_param("ssssssi", $fname, $lname, $email, $phone, $gender, $usertype, $id);
    }

    // Execute the update and provide meaningful feedback
    if ($stmt->execute()) {
        // Show success message and redirect
        echo "<script>
                window.alert('User information successfully updated!');
                window.location.href = 'staff_user.php';
              </script>";
    } else {
        // Show error message and stay on the page
        echo "<script>
                window.alert('Error: Could not update the user information. Please try again.');
                window.location.href = 'staff_edit_user.php?id=$id';
              </script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update User</title>
    <link rel="stylesheet" href="dashboardstyle.css">
    <link rel="icon" type="image/x-icon" href="images/logo.jpg">

    <script>
    //function to validate form inputs
    function validate() {
      const fname = document.editUserForm.fname.value;
      const lname = document.editUserForm.lname.value;
      const email = document.editUserForm.email.value;
      const phone = document.editUserForm.phone.value;
      const gender = document.editUserForm.gender.value;
      const password = document.editUserForm.pw.value;

      if (fname == "") {
        alert("Please provide your first name");
        return false;
      }
      if (lname == "") {
        alert("Please provide your last name");
        return false;
      }
      if (email == "") {
        alert("Please provide your email");
        return false;
      }
      if (gender == "") {
        alert("Please select your gender");
        return false;
      }
      if (email.indexOf("@") <= 1 || 
          (document.editUserForm.email.value.lastIndexOf(".") - document.editUserForm.email.value.indexOf("@") < 2)) {
        alert("Please enter a correct email address");
        return false;
      }
      if (phone.length != 10 || isNaN(phone)) {
                alert("Please enter a valid contact number");
                return false;
      }
      // Validate password only if it is not blank
      if (password !== "") {
          if (password.length < 8) {
              alert("Password must contain at least 8 characters");
              return false;
          }
          if (!/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*(),.?":{}|<>]).{8,}$/.test(password)) {
              alert("Password must contain at least one uppercase letter, one lowercase letter, one number, one special character, and be at least 8 characters long.");
              return false;
          }
      }
      document.editUserForm.submit();
      return true;
    }
  </script>
</head>
<body>

<header>
  <div class="logo-section">
    <div class="logo">Staff Dasboard - FitZone Fitness Center</div>
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
  <h2>Edit User</h2>
    <form action="staff_edit_user.php" method="POST" name="editUserForm" onsubmit="return validate()">
        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">

        <label>First Name:</label>
        <input type="text" name="fname" value="<?php echo $user['fname']; ?>" required><br>

        <label>Last Name:</label>
        <input type="text" name="lname" value="<?php echo $user['lname']; ?>" required><br>

        <label>Email:</label>
        <input type="email" name="email" value="<?php echo $user['email']; ?>" required><br>

        <label>Contact No:</label>
        <input type="text" name="phone" value="<?php echo $user['phone']; ?>" required><br>

        <label>Gender:</label>
        <select name="gender">
        <option value="Male" <?php if ($user['gender'] == 'Male') echo 'selected'; ?>>Male</option>
        <option value="Female" <?php if ($user['gender'] == 'Female') echo 'selected'; ?>>Female</option>
        <option value="Other" <?php if ($user['gender'] == 'Other') echo 'selected'; ?>>Other</option>
        </select><br>

        <label>New Password (leave blank to keep current):</label>
        <input type="password" name="pw"><br>

        <label>User Type:</label>
        <select name="usertype">
          <option value="Customer" <?php if($user['userType'] == 'Customer') echo 'selected'; ?>>Customer</option>
        </select>
        <br>

        <button type="submit" class="submit-button">Submit</button>
        <button type="button" class="back-button" onclick="window.location.href='staff_user.php'">Back</button>
    </form>
</div>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>

