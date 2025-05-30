<?php
session_start();
include('connection.php');
$isLoggedIn = isset($_SESSION['user_id']);

// Set $user_id only if the user is logged in
$user_id = $isLoggedIn ? $_SESSION['user_id'] : null;

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
       // If not logged in, redirect to signup
       if (!$isLoggedIn) {
        echo "<script>alert('Please log in to send a message.'); window.location.href='signup.html';</script>";
        exit();
    }
// Retrieve form data
$full_name = $_POST['fullName'];
$email = $_POST['email'];
$mobile = $_POST['mobile'];
$message = $_POST['msg'];
$respond = 'Pending'; // default response status

$sql = "INSERT INTO customer_queries (user_id, full_name, email, mobile, message, respond) 
        VALUES ('$user_id', '$full_name', '$email', '$mobile', '$message', '$respond')";

// Execute the query
if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Message sent successfully!'); window.location.href='contact.php';</script>";
    exit();
} else {
    echo "<script>alert('There was an error booking for the training: " . $conn->error . "'); window.location.href='contact.php';</script>";
}
}

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact</title>
  <link rel="icon" type="image/x-icon" href="images/logo.jpg">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <script>

function validate() {
    const fullName = document.getElementById("fullName").value;
    const email = document.getElementById("email").value;
    const mobile = document.getElementById("mobile").value;
    const message = document.getElementById("msg").value;

    if (fullName === "") {
        alert("Please provide your full name.");
        return false;
    }
    if (email === "") {
        alert("Please provide your email address.");
        return false;
    }
    if (mobile === "") {
        alert("Please provide your contact number.");
        return false;
    }
    if (mobile.length !== 10) {
        alert("Please provide valid contact number.");
        return false;
    }
    if (message === "") {
        alert("Please provide a message.");
        return false;
    }
    if (email.indexOf("@") <= 1 || 
        (email.lastIndexOf(".") - email.indexOf("@") < 2)) {
        alert("Please enter a correct email address.");
        return false;
    }
    return true;
}
</script>

</head>
<body>
<header class="header">
    <nav class="navigation">
      <img src="images/logo.jpg" alt="Logo" class="logo">
      <ul>
          <li><a href="home.php">Home</a></li>
          <li><a href="about.php">About</a></li>
          <li><a href="membership.php">Memberships</a></li>
          <li><a href="classes.php">Classes</a></li>
          <li><a href="training.php">Personal Training</a></li>
          <li><a href="blog.php">Blog</a></li>
          <?php if ($isLoggedIn): ?>
              <li><a href="contact.php" class="active">Contact Us</a></li>
          <?php else: ?>
              <li><a href="signup.html" class="active">Contact Us</a></li>
          <?php endif; ?>
          <?php if ($isLoggedIn): ?>
            <?php
                $myAccountUrl = 'customer.php'; // Default My Account URL
                if (isset($_SESSION['user_type'])) {
                    switch ($_SESSION['user_type']) {
                        case 'Admin':
                            $myAccountUrl = 'admin_user.php';
                            break;
                        case 'Staff':
                            $myAccountUrl = 'staff_user.php';
                            break;
                        case 'Customer':
                            $myAccountUrl = 'customer.php';
                            break;
                        default:
                            $myAccountUrl = 'customer.php';
                            break;
                    }
                }
                ?>
                <li><a href="<?php echo $myAccountUrl; ?>">My Account</a></li>
          <?php else: ?>
              <li><a href="login.html">My Account</a></li>
          <?php endif; ?>
          <?php if ($isLoggedIn): ?>
              <li><a href="logout.php" class="register-link">Logout</a></li>
          <?php else: ?>
              <li><a href="signup.html" class="register-link">Sign up</a></li>
          <?php endif; ?>
      </ul>
    </nav>
  </header>

  <main class="contact-section">
  <div class="contact-container">
    <div class="contact-form">
      <h2>Contact Us</h2>
    <form method="POST" action="contact.php" onsubmit="return validate()">
    <label for="fullName">
        <i class="fa fa-user"></i> Full Name:
    </label>
    <input type="text" id="fullName" name="fullName" required>

    <label for="email">
        <i class="fa fa-envelope"></i> Email Address:
    </label>
    <input type="email" id="email" name="email" required>

    <label for="mobile">
        <i class="fa fa-phone"></i> Contact No:
    </label>
    <input type="tel" id="mobile" name="mobile" required>

    <label for="msg">
        <i class="fa fa-comment"></i> Message:
    </label>
    <textarea id="msg" name="msg" rows="5" required></textarea>

    <button type="submit" id="submitBtn">Submit</button>
    </form>
    </div>
    <div class="contact-map">
        <iframe src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8&q=ICBT%20Gampaha%20Campus&zoom=15&maptype=roadmap"
            width="100%" height="400" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
        </iframe>
    </div> 
  </div>
</main>

<?php include 'footer.php'; ?> 
  
</body>
</html>