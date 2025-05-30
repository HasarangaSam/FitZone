<?php
session_start();

include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $contact_no = $_POST['contact_no']; 
    $gender = $_POST['gender'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $usertype = $_POST['usertype']; 

    // Check if usertype is selected
    if (empty($usertype)) {
        echo "<script>
                window.alert('Please select a valid user type.');
                window.location.href = 'add_user.php';
              </script>";
        exit();
    }

    // Prepare and execute SQL insert statement
    $stmt = $conn->prepare("INSERT INTO users (fname, lname, email, phone, gender, password, usertype) VALUES (?, ?, ?, ?, ?,?,?)");
    $stmt->bind_param("sssssss", $fname, $lname, $email, $contact_no, $gender, $password, $usertype);

    if ($stmt->execute()) {
        // Show success message and redirect
        echo "<script>
                window.alert('User successfully added!');
                window.location.href = 'admin_user.php';
              </script>";
    } else {
        // Show error message if there is a problem with the database operation
        echo "<script>
                window.alert('Error: Could not add the user. Please try again.');
                window.location.href = 'admin_user.php';
              </script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New User</title>
    <link rel="stylesheet" href="dashboardstyle.css">
    <link rel="icon" type="image/x-icon" href="images/logo.jpg">
    <script>
        function validate() {
            const fname = document.add_user_form.fname.value;
            const lname = document.add_user_form.lname.value;
            const email = document.add_user_form.email.value;
            const password = document.add_user_form.password.value;
            const confirmPass = document.add_user_form.cpw.value;
            const contact_no = document.add_user_form.contact_no.value;
            const gender = document.add_user_form.gender.value;

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
            if (contact_no.length != 10 || isNaN(contact_no)) {
                alert("Please enter a valid contact number");
                return false;
            }
            if (password == "") {
                alert("Please provide a password");
                return false;
            }
            if (confirmPass == "") {
                alert("Please provide the confirm password");
                return false;
            }
            if (email.indexOf("@") <= 1 || 
                (email.lastIndexOf(".") - email.indexOf("@") < 2)) {
                alert("Please enter a correct email address");
                return false;
            }
            if (password.length < 8) {
                alert("Password must contain at least 8 characters");
                return false;
            }
            if (!/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*(),.?":{}|<>]).{8,}$/.test(password)) {
                alert("Password must contain at least one uppercase letter, one lowercase letter, one number, one special character, and be at least 8 characters long.");
                return false;
            }
            if (confirmPass != password) {
                alert("Confirm password doesn't match");
                return false;
            }

            return true;
        }
  </script>
</head>
<body>

<header>
  <div class="logo-section">
    <div class="logo">Admin - FitZone Fitness Center</div>
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
  <h2>Add New User</h2>
    <form action="add_user.php" name="add_user_form" method="POST" onsubmit="return validate();">
        <label>First Name:</label>
        <input type="text" name="fname" required><br>

        <label>Last Name:</label>
        <input type="text" name="lname" required><br>

        <label>Email:</label>
        <input type="email" name="email" required><br>

        <label>Contact:</label>
        <input type="text" name="contact_no" required><br>

        <label>Gender:</label>
        <select name="gender">
            <option value="">Select Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select><br>

        <label>Password:</label>
        <input type="password" name="password" required><br>

        <label>Confirm Password:</label>
        <input type="password" name="cpw" required><br>


        <label>User Type:</label>
        <select name="usertype" required>
            <option value="">Select user type</option> 
            <option value="Admin">Admin</option>
            <option value="Staff">Staff</option>
            <option value="Customer">Customer</option>
        </select>

        <button type="submit" class="submit-button">Submit</button>
        <button type="button" class="back-button" onclick="window.location.href='admin_user.php'">Back</button>
    </form>
</div>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>

