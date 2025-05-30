<?php 
include 'connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: login.html"); // Redirect to login if not authenticated
  exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve POST data
    $program_name = $_POST['program_name'];
    $description = $_POST['description'];
    $specialties = $_POST['specialties'];
    
    // File upload handling
    $target_dir = "images/";
    $image = $_FILES['image']['name'];
    $target_file = $target_dir . basename($image);

    if ($_FILES['image']['error'] === UPLOAD_ERR_OK && move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        // Include the directory path in the image variable for database storage
        $image_path = $target_dir . $image; // This will store the path for the database

        // Prepare and execute SQL insert statement
        $stmt = $conn->prepare("INSERT INTO fitness_programs (program_name, description, specialties, image) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $program_name, $description, $specialties, $image_path);
        
        if ($stmt->execute()) {
            echo "<script>alert('Class successfully added!'); window.location.href = 'admin_class.php';</script>";
        } else {
            echo "<script>alert('Error: Could not add the class. Please try again.'); window.location.href = 'admin_class.php';</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Error: Could not upload the image. Please try again.'); window.location.href = 'admin_class.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Class</title>
    <link rel="stylesheet" href="dashboardstyle.css">
    <link rel="icon" type="image/x-icon" href="images/logo.jpg">
    <script>
      function validateForm() {
          const programName = document.add_class_form.program_name.value.trim();
          const description = document.add_class_form.description.value.trim();
          const specialties = document.add_class_form.specialties.value.trim();
          const price = document.add_class_form.price.value.trim();
          const image = document.add_class_form.image;

          if (programName === "") {
              alert("Please enter the Class Name.");
              document.add_class_form.program_name.focus();
              return false;
          }

          if (description === "") {
              alert("Please enter the Description.");
              document.add_class_form.description.focus();
              return false;
          }

          if (specialties === "") {
              alert("Please enter the Specialties.");
              document.add_class_form.specialties.focus();
              return false;
          }

          // Image validation
          if (image.files.length > 0 && !image.files[0].type.match('image.*')) {
              alert("Please select a valid image file.");
              document.add_class_form.image.focus();
              return false;
          }

          return true; // If all fields are valid
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
    <h2>Add New Class</h2>
    <form action="add_class.php" name="add_class_form" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
        <label>Class Name:</label>
        <input type="text" name="program_name"><br>

        <label>Description:</label>
        <textarea name="description"></textarea><br>

        <label>Specialities:</label>
        <input type="text" name="specialties"><br>

        <label>Image:</label>
        <input type="file" accept="image/*" name="image"><br>

        <button type="submit" class="submit-button">Submit</button>
        <button type="button" class="back-button" onclick="window.location.href='admin_class.php'">Back</button>
    </form>
</div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
