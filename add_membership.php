<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.html"); // Redirect to login if not authenticated
  exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $membership_name = $_POST['plan_name'];
    $description = $_POST['description'];
    $benefits = $_POST['benefits'];
    $features = $_POST['features'];
    $price = $_POST['price'];
    $promotions = $_POST['promotions'];

    // Handle file upload
    $image = $_FILES['image']['name'];
    $target_dir = "images/";
    $target_file = $target_dir . basename($image);

    // Move the uploaded file to the target directory
    if ($_FILES['image']['error'] === UPLOAD_ERR_OK && move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        // Include the directory path in the image variable for database storage
        $image_path = $target_dir . $image; // This will store the path for the database

        // Prepare and execute SQL insert statement
        $stmt = $conn->prepare("INSERT INTO membership_plans (plan_name, description, benefits, features, price, promotions, image) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $membership_name, $description, $benefits, $features, $price, $promotions, $image_path);

        if ($stmt->execute()) {
            // Show success message and redirect
            echo "<script>
                    window.alert('Membership successfully added!');
                    window.location.href = 'admin_membership.php';
                  </script>";
        } else {
            // Show error message if there is a problem with the database operation
            echo "<script>
                    window.alert('Error: Could not add the membership. Please try again.');
                    window.location.href = 'admin_membership.php';
                  </script>";
        }
        $stmt->close();
    } else {
        // Handle image upload error
        echo "<script>
                window.alert('Error: Could not upload the image. Please try again.');
                window.location.href = 'admin_membership.php';
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Mebership</title>
    <link rel="stylesheet" href="dashboardstyle.css">
    <link rel="icon" type="image/x-icon" href="images/logo.jpg">
    <script>
function validateForm() {
    const form = document.forms["add_membership_form"];
    const membershipName = form["plan_name"].value.trim();
    const description = form["description"].value.trim();
    const benefits = form["benefits"].value.trim();
    const features = form["features"].value.trim();
    const price = form["price"].value.trim();
    const promotions = form["promotions"].value.trim();
    const image = form["image"].files.length;

    if (membershipName === "") {
        alert("Please enter the membership name.");
        return false;
    }
    if (description === "") {
        alert("Please enter the description.");
        return false;
    }
    if (benefits === "") {
        alert("Please enter the benefits.");
        return false;
    }
    if (features === "") {
        alert("Please enter the features.");
        return false;
    }
    if (price === "") {
        alert("Please enter the price.");
        return false;
    }
    if (isNaN(price) || price <= 0) {
        alert("Please enter a valid price greater than 0.");
        return false;
    }
    if (promotions === "") {
        alert("Please enter the promotions.");
        return false;
    }
    if (image === 0) {
        alert("Please upload an image.");
        return false;
    }

    // If all validations pass, allow form submission
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
      <div class="nav-item" onclick="window.location.href='admin_user.php'">
        <img src="images/team.png" class="nav-icon" alt="Manage Users">
        <h3>Manage Users</h3>
      </div>
      <div class="nav-item active" onclick="window.location.href='admin_membership.php'">
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
    <h2>Add New Mebership</h2>
    <form action="add_membership.php" name="add_membership_form"  method="POST" enctype="multipart/form-data"  onsubmit="return validateForm()">
        <label>Membership Name:</label>
        <input type="text" name="plan_name"><br>

        <label>Description:</label>
        <textarea name="description"></textarea><br>

        <label>Benefits:</label>
        <input type="text" name="benefits"><br>

        <label>Features:</label>
        <input type="text" name="features"><br>

        <label>Price:</label>
        <input type="text" name="price"><br>

        <label>Promotions:</label>
        <input type="text" name="promotions"><br>

        <label>Image:</label>
        <input type="file" name="image"><br>

        <button type="submit" class="submit-button">Submit</button>
        <button type="button" class="back-button" onclick="window.location.href='admin_membership.php'">Back</button>
    </form>
</div>
</body>
</html>