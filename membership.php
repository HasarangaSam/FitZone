<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
include('connection.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Memberships</title>
  <link rel="icon" type="image/x-icon" href="images/logo.jpg">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>

    //Function to confirm to redirecting to login
    function confirmLogin() {
        if (confirm("You need to login before registering. Do you want to proceed to the login page?")) {
            window.location.href = 'login.html';
        }
    }

    //Function to confirm registration
    function confirmRegistration(planId) {
        if (confirm('Are you sure you want to register for this membership plan?')) {
            window.location.href = 'register_for_plan.php?plan_id=' + planId;
        }
    }

    function toggleMenu() {
    const navigation = document.querySelector('.navigation');
    navigation.classList.toggle('active'); 

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
          <li><a href="membership.php" class="active">Memberships</a></li>
          <li><a href="classes.php">Classes</a></li>
          <li><a href="training.php">Personal Training</a></li>
          <li><a href="blog.php">Blog</a></li>
          <li><a href="contact.php">Contact Us</a></li>
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
      <div class="menu-toggle" onclick="toggleMenu()">
            <i class="fas fa-bars" style="color: white;"></i> 
      </div>
    </nav>
  </header>

  <main>

  <?php
    // Fetch data from the membership_plans table
    $sql = "SELECT id, plan_name, description, benefits, price, promotions, features, image FROM membership_plans";
    $result = $conn->query($sql);
    ?>

<section class="membership-section">
    <div class="container">
        <h2 class="section-title">Our Membership Plans</h2>
        <div class="membership-cards">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="membership-card">
                        <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['plan_name']) ?>">
                        <div class="card-content">
                            <h3><?= htmlspecialchars($row['plan_name']) ?></h3>
                            <p><?= htmlspecialchars($row['description']) ?></p>
                            <p><strong>Benefits:</strong> <?= htmlspecialchars($row['benefits']) ?></p>
                            <p><strong>Features:</strong> <?= htmlspecialchars($row['features']) ?></p>
                            <p><strong>Price:</strong> Rs.<?= number_format($row['price'], 2) ?> per month</p>

                            <?php if (!empty($row['promotions'])): ?>
                                <p class="promotion"><strong>Special Promotion:</strong> <?= htmlspecialchars($row['promotions']) ?></p>
                            <?php endif; ?>

                            <?php if ($isLoggedIn): ?>
                                <button class="btn-register" onclick="confirmRegistration(<?= $row['id'] ?>)"><b>Register</b></button>
                            <?php else: ?>
                                <button class="btn-register" onclick="confirmLogin()"><b>Sign Up</b></button>
                            <?php endif; ?>
                        </div> <!-- Close card-content -->
                    </div> <!-- Close membership-card -->
                <?php endwhile; ?>
            <?php else: ?>
                <p>No membership plans found.</p>
            <?php endif; ?>
        </div> <!-- Close membership-cards -->
    </div> <!-- Close container -->
</section>

  </main>

  <?php include 'footer.php'; ?>

</body>
</html>