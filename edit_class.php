<?php
session_start();

include 'connection.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.html"); // Redirect to login if not authenticated
  exit();
}

// Retrieve the 'id' parameter from the URL and assign it to the $id variable
if (isset($_GET['id'])) {
  $id = $_GET['id'];
   // Execute an SQL query to select the record from the 'fitness_programs' table that matches the given id
  $result = $conn->query("SELECT * FROM fitness_programs WHERE id=$id");
   // Fetch the result as an associative array and store it in the $class variable
  $class = $result->fetch_assoc();
}

//Get Form Data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $program_name = $_POST['program_name'];
    $description = $_POST['description'];
    $specialties = $_POST['specialties'];

    // Check if an image file is uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $image = $_FILES['image']['name'];
        $target_dir = "images/";
        $target_file = $target_dir . basename($image);

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            // If file upload is successful, update the database with the new image
            $stmt = $conn->prepare("UPDATE fitness_programs SET program_name=?, description=?, specialties=?,  image=? WHERE id=?");
            $stmt->bind_param("ssssi", $program_name, $description, $specialties, $target_file, $id);
        } else {
            echo "<script>
                    window.alert('Error: Could not upload the image. Please try again.');
                    window.location.href = 'edit_class.php?id=$id';
                  </script>";
            exit;
        }
    } else {
        // If no new image is uploaded, keep the existing image
        $stmt = $conn->prepare("UPDATE fitness_programs SET program_name=?, description=?, specialties=? WHERE id=?");
        $stmt->bind_param("sssi", $program_name, $description, $specialties,  $id);
    }

    // Execute the statement and handle the result
    if ($stmt->execute()) {
        echo "<script>
                window.alert('Class information successfully updated!');
                window.location.href = 'admin_class.php';
              </script>";
    } else {
        echo "<script>
                window.alert('Error: Could not update the class information. Please try again.');
                window.location.href = 'edit_class.php?id=$id';
              </script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Class</title>
    <link rel="stylesheet" href="dashboardstyle.css">
    <link rel="icon" type="image/x-icon" href="images/logo.jpg">
    <link rel="icon" type="image/x-icon" href="images/logo.jpg">
    <script>
      //function to validate form input
    function validateForm() {
        const programName = document.edit_membership_form.program_name.value;
        const description = document.document.edit_membership_form.description.value;
        const price = document.edit_membership_form.price.value;
        const image = document.document.edit_membership_form.image.value;

        const allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;

        // Check if required fields are empty
        if (programName === "") {
            alert("Program name is required.");
            return false;
        }
        if (description === "") {
            alert("Description is required.");
            return false;
        }
        if (!price || isNaN(price) || price <= 0) {
            alert("Please enter a valid price.");
            return false;
        }

        // Image validation if a file is selected
        if (image && !allowedExtensions.exec(image)) {
            alert("Only JPG, JPEG, and PNG files are allowed for the image.");
            return false;
        }

        return true; // If all checks pass
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
  <h2>Edit Class</h2>
    <form action="edit_class.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
        <input type="hidden" name="id" value="<?php echo $class['id']; ?>">

        <label>Class Name:</label>
        <input type="text" name="program_name" value="<?php echo $class['program_name']; ?>"><br>

        <label>Description:</label>
        <textarea name="description"><?php echo $class['description']; ?></textarea><br> 

        <label>Specialities:</label>
        <input type="text" name="specialties" value="<?php echo $class['specialties']; ?>"><br>

        <label>Image:</label>
        <input type="file" name="image" value="<?php echo $class['image']; ?>"><br>

        <button type="submit" class="submit-button">Submit</button>
        <button type="button" class="back-button" onclick="window.location.href='admin_class.php'">Back</button>
    </form>
  </div>
</div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>