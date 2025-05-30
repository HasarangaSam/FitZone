<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.html"); // Redirect to login if not authenticated
  exit();
}

// Fetch membership details based on the ID from the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $conn->query("SELECT * FROM membership_plans WHERE id=$id");
    $membership = $result->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $plan_name = $_POST['plan_name'];
    $description = $_POST['description'];
    $benefits = $_POST['benefits'];
    $features = $_POST['features'];
    $price = $_POST['price'];
    $promotions = $_POST['promotions'];

    // Check if an image file is uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $image = $_FILES['image']['name'];
        $target_dir = "images/";
        $target_file = $target_dir . basename($image);

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            // Prepare the SQL statement to update the membership plan with the new image
            $stmt = $conn->prepare("UPDATE membership_plans SET plan_name=?, description=?, benefits=?, features=?, price=?, promotions=?, image=? WHERE id=?");
            $stmt->bind_param("sssssssi", $plan_name, $description, $benefits, $features, $price, $promotions, $target_file, $id);
        } else {
            echo "<script>
                    window.alert('Error: Could not upload the image. Please try again.');
                    window.location.href = 'edit_membership.php?id=$id';
                  </script>";
            exit;
        }
    } else {
        // If no new image is uploaded, keep the existing image
        $stmt = $conn->prepare("UPDATE membership_plans SET plan_name=?, description=?, benefits=?, features=?, price=?, promotions=? WHERE id=?");
        $stmt->bind_param("ssssssi", $plan_name, $description, $benefits, $features, $price, $promotions, $id);
    }

    // Execute the update and provide meaningful feedback
    if ($stmt->execute()) {
        echo "<script>
                window.alert('Membership information successfully updated!');
                window.location.href = 'admin_membership.php';
              </script>";
    } else {
        echo "<script>
                window.alert('Error: Could not update the membership information. Please try again.');
                window.location.href = 'edit_membership.php?id=$id';
              </script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Membership</title>
    <link rel="stylesheet" href="dashboardstyle.css">
    <link rel="icon" type="image/x-icon" href="images/logo.jpg">
    <script>
      //function to validate form inputs
      function validateForm() {
        const planName = document.edit_membership_form.plan_name.value;
        const description = document.edit_membership_form.description.value;
        const benefits = document.edit_membership_form.benefits.value;
        const features = document.edit_membership_form.features.value;
        const price = document.edit_membership_form.price.value;
        const promotions = document.edit_membership_form.promotions.value;
        const image = document.edit_membership_form.image;

        if (planName === "") {
          alert("Please enter the Membership Name.");
          document.edit_membership_form.plan_name.focus();
          return false;
        }

        if (description === "") {
          alert("Please enter the Description.");
          document.edit_membership_form.description.focus();
          return false;
        }

        if (benefits === "") {
          alert("Please enter the Benefits.");
          document.edit_membership_form.benefits.focus();
          return false;
        }

        if (features === "") {
          alert("Please enter the Features.");
          document.edit_membership_form.features.focus();
          return false;
        }

        if (price === "") {
          alert("Please enter the Price.");
          document.edit_membership_form.price.focus();
          return false;
        }

        // Check if price is a valid number
        if (isNaN(price) || Number(price) <= 0) {
          alert("Please enter a valid positive number for the Price.");
          document.edit_membership_form.price.focus();
          return false;
        }

        if (promotions === "") {
          alert("Please enter the Promotions.");
          document.edit_membership_form.promotions.focus();
          return false;
        }

        //image validation
        if (image.files.length > 0 && !image.files[0].type.match('image.*')) {
          alert("Please select a valid image file.");
          document.edit_membership_form.image.focus();
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
    </nav>
  </aside>

  <div class="main-content">
    <h2>Edit Membership</h2>
    <form action="edit_membership.php" name="edit_membership_form" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">

      <input type="hidden" name="id"  value="<?php echo $membership['id']; ?>">

      <label>Membership Name:</label>
      <input type="text" name="plan_name" value="<?php echo $membership['plan_name']; ?>"><br>

      <label>Description:</label>
      <textarea name="description" required><?php echo $membership['description']; ?></textarea><br>

      <label>Benefits:</label>
      <input type="text" name="benefits" value="<?php echo $membership['benefits']; ?>" required><br>

      <label>Features:</label>
      <input type="text" name="features" value="<?php echo $membership['features']; ?>" required><br>

      <label>Price:</label>
      <input type="text" name="price" value="<?php echo $membership['price']; ?>" required><br>

      <label>Promotions:</label>
      <input type="text" name="promotions" value="<?php echo $membership['promotions']; ?>" required><br>

      <label>Image:</label>
      <input type="file" name="image" accept="image/*"><br>

      <button type="submit" class="submit-button">Submit</button>
      <button type="button" class="back-button" onclick="window.location.href='admin_membership.php'">Back</button>
    </form>
  </div>
</div>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
