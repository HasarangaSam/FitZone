<?php
include('connection.php');

session_start();
$isLoggedIn = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Blog</title>
  <link rel="icon" type="image/x-icon" href="images/logo.jpg">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
          <li><a href="blog.php" class="active">Blog</a></li>
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
    </nav>
  </header>

  <main>
    <section class="blog">
      <h1>Our Blog</h1>
      <div class="blog-container">
        <div class="blog-card">
          <img src="images/workout.jpg" alt="4-Week Workout Plan" class="blog-image">
          <h3>Kickstart Your Fitness Journey: 4-Week Workout Plan</h3>
          <p class="blog-description">Discover a structured 4-week workout plan designed for beginners. This program combines strength training, cardio, and flexibility exercises to help you build a solid foundation for your fitness journey.</p>
          <a href="workout.html" target="blank" class="read-more">Read More</a>
        </div>
        <div class="blog-card">
          <img src="images/salad.jpg" alt="Quinoa Salad" class="blog-image">
          <h3>Delicious Quinoa Salad: A Nutrient-Packed Meal</h3>
          <p class="blog-description">Try this refreshing quinoa salad, packed with vibrant veggies, protein-rich chickpeas, and a zesty lemon dressing. Perfect for a light lunch or a side dish!</p>
          <a href="salad.html" target="blank" class="read-more">Read More</a>
        </div>
        <div class="blog-card">
          <img src="images/meal.jpg" alt="Weekly Meal Prep" class="blog-image">
          <h3>Weekly Meal Prep: Simplifying Healthy Eating</h3>
          <p class="blog-description">Meal prepping can save you time and help you stick to your nutritional goals. This blog provides a simple weekly meal plan with easy-to-make recipes.</p>
          <a href="meal.html" target="blank" class="read-more">Read More</a>
        </div>
        <div class="blog-card">
          <img src="images/sarasuccess.jpg" alt="Sarah’s Success Story" class="blog-image">
          <h3>Transforming Lives: Sarah’s Inspiring Weight Loss Journey</h3>
          <p class="blog-description">Read Sarah's incredible transformation story as she shares her journey from struggling with weight to achieving her fitness goals. Get inspired!</p>
          <a href="sara.html" target="blank" class="read-more">Read More</a>
        </div>
      </div>
    </section>    
  </main>

  <?php include 'footer.php'; ?>
  
</body>
</html>