<?php
session_start();

include 'connection.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.html"); // Redirect to login if not authenticated
  exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $conn->query("SELECT * FROM personal_training WHERE id=$id");
    $trainer = $result->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $trainer_name = $_POST['trainer_name'];
    $description = $_POST['description'];
    $specialties = $_POST['specialties'];
    $price = $_POST['price'];

    // Check if an image file is uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $image = $_FILES['image']['name'];
        $target_dir = "images/";
        $target_file = $target_dir . basename($image);

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            // If file upload is successful, update the database with the new image
            $stmt = $conn->prepare("UPDATE personal_training SET trainer_name=?, description=?, specialties=?, price=?, image=? WHERE id=?");
            $stmt->bind_param("sssdsi", $trainer_name, $description, $specialties, $price, $target_file, $id);
        } else {
            echo "<script>
                    window.alert('Error: Could not upload the image. Please try again.');
                    window.location.href = 'edit_class.php?id=$id';
                  </script>";
            exit;
        }
    } else {
        // If no new image is uploaded, keep the existing image
        $stmt = $conn->prepare("UPDATE personal_training SET trainer_name=?, description=?, specialties=?, price=? WHERE id=?");
        $stmt->bind_param("sssdi", $trainer_name, $description, $specialties, $price, $id);
    }

    // Execute the statement and handle the result
    if ($stmt->execute()) {
        echo "<script>
                window.alert('Trainer information successfully updated!');
                window.location.href = 'admin_trainer.php';
              </script>";
    } else {
        echo "<script>
                window.alert('Error: Could not update the trainer information. Please try again.');
                window.location.href = 'edit_trainer.php?id=$id';
              </script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Trainer</title>
    <link rel="stylesheet" href="dashboardstyle.css">
    <link rel="icon" type="image/x-icon" href="images/logo.jpg">

    <script>
      function validateForm() {
          const trainerName = document.edit_trainer_form.trainer_name.value;
          const description = document.edit_trainer_form.description.value;
          const specialties = document.edit_trainer_form.specialties.value;
          const price = document.edit_trainer_form.price.value;
          const image = document.edit_trainer_form.image;

          if (trainerName === "") {
              alert("Please enter the Trainer Name.");
              document.edit_trainer_form.trainer_name.focus();
              return false;
          }

          if (description === "") {
              alert("Please enter the Description.");
              document.edit_trainer_form.description.focus();
              return false;
          }

          if (specialties === "") {
              alert("Please enter the Specialties.");
              document.edit_trainer_form.specialties.focus();
              return false;
          }

          if (price === "") {
              alert("Please enter the Price.");
              document.edit_trainer_form.price.focus();
              return false;
          }

          // Check if price is a valid number
          if (isNaN(price) || Number(price) <= 0) {
              alert("Please enter a valid positive number for the Price.");
              document.edit_trainer_form.price.focus();
              return false;
          }

          // Image validation
          if (image.files.length > 0 && !image.files[0].type.match('image.*')) {
              alert("Please select a valid image file.");
              document.edit_trainer_form.image.focus();
              return false;
          }

          return true; // If all fields are valid
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
    </nav>
  </aside>

  <div class="main-content">
  <h2>Edit Personal Trainers</h2>
    <form action="edit_trainer.php" name="edit_trainer_form" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
        <input type="hidden" name="id" value="<?php echo $trainer['id']; ?>">

        <label>Trainer Name:</label>
        <input type="text" name="trainer_name" value="<?php echo $trainer['trainer_name']; ?>"><br>

        <label>Description:</label>
        <textarea name="description" required><?php echo $trainer['description']; ?></textarea><br>

        <label>Specialities:</label>
        <input type="text" name="specialties" value="<?php echo $trainer['specialties']; ?>"><br>

        <label>Price:</label>
        <input type="text" name="price" value="<?php echo $trainer['price']; ?>"><br>

        <label>Image:</label>
        <input type="file" name="image" value="<?php echo $trainer['image']; ?>"><br>

        <button type="submit" class="submit-button">Submit</button>
        <button type="button" class="back-button" onclick="window.location.href='admin_trainer.php'">Back</button>
    </form>
  </div>
</div>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>