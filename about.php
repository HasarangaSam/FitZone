<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About</title>
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
          <li><a href="about.php" class="active">About</a></li>
          <li><a href="membership.php">Memberships</a></li>
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
    </nav>
  </header>

  <main>
    <section class="about-us">
        <section class="large-image">
            <img src="images/about.jpg" alt="FitZone Fitness Center" class="img-large">
        </section>
        <h1>About Us</h1>
        <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;At FitZone Fitness Center, we believe that fitness is a journey—a path to achieving not only physical strength but also a balanced and fulfilling life. Located in the heart of Kurunegala, FitZone is a place where people from all walks of life and fitness backgrounds come together with a shared goal: improving health and well-being. We aim to create an environment that is not only supportive but also motivating, empowering our members to reach their unique fitness goals.</p>
        <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Our facility offers a broad selection of fitness programs designed to cater to individual needs, whether you’re looking to build muscle, boost endurance, or maintain an active lifestyle. FitZone is equipped with top-of-the-line machines, weights, and cardio equipment to support varied workout styles and goals. For those who prefer a more tailored approach, our experienced trainers are available to provide one-on-one guidance, helping members set and achieve personalized targets. Our dynamic group classes, including options for strength training, cardio, yoga, and flexibility, are ideal for those who thrive in a group setting with shared energy and motivation.</p>
        <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;To make it easy and convenient for our members, we’ve built a website that allows you to manage your fitness experience at FitZone. Members can explore class schedules, sign up for training sessions, and stay connected with the FitZone team through our online platform. Additionally, we regularly share a range of resources to keep you inspired and informed, from effective workout routines to balanced meal plans, healthy recipes, and uplifting success stories from fellow members.</p>
        <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Whether you’re just beginning your fitness journey or looking to take your routine to the next level, FitZone Fitness Center is here to support you every step of the way. Join us and discover how great it feels to be part of a community dedicated to health, strength, and well-being.</p>
    </section>

    <section class="mission-vision">
          <h2>Mission & Vision</h2>
          <div class="mission-vision-container">
              <div class="mission">
                  <h3>Our Mission</h3>
                  <p>To empower individuals to achieve their fitness goals by providing top-notch facilities, personalized training, and a supportive community.</p>
              </div>
              <div class="vision">
                  <h3>Our Vision</h3>
                  <p>To be the leading fitness center in Kurunegala, inspiring a healthier lifestyle and fostering a community where everyone can thrive.</p>
              </div>
          </div>
      </section>
      
         <section class="founders-story">
          <h1>Founder's Story</h1>
          <div class="founder-content">
              <img src="images/founder-image.jpg" alt="Founders of FitZone Fitness Center" class="founder-image">
              <div class="founder-text">
                  <p>Our journey began with a shared passion for fitness and well-being. The founders, Mr.Jagath Silva and Mr.Kamal Gamage, were inspired to create a space that not only promotes physical health but also fosters a sense of community among fitness enthusiasts. With years of experience in the fitness industry, they understood the challenges individuals face when trying to maintain a balanced lifestyle.</p>
                  <p>Driven by a desire to help others achieve their fitness goals, they envisioned FitZone Fitness Center as a welcoming environment where everyone, regardless of their fitness level, can feel motivated and supported. Their mission is to empower individuals through personalized training, group classes, and nutrition counseling, ensuring that every member receives the guidance they need to thrive.</p>
              </div>
          </div>
          </section>
      </section>
      
        <section class="awards">
          <h2>Awards & Achievements</h2>
          <div class="awards-container">
              <div class="award-card">
                  <h3>Best Gym of the Year</h3>
                  <p>Awarded for outstanding services and customer satisfaction in 2023.</p>
              </div>
              <div class="award-card">
                  <h3>Top Fitness Trainer</h3>
                  <p>Recognized for excellence in personal training and client success.</p>
              </div>
              <div class="award-card">
                  <h3>Innovation in Fitness</h3>
                  <p>Honored for introducing innovative fitness programs and technology.</p>
              </div>
          </div>
      </section>
</main>
<?php include 'footer.php'; ?>        
</body>
</html>
