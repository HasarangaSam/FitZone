<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home</title>
  <link rel="icon" type="image/x-icon" href="images/logo.jpg">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <script>
      let currentHomeIndex = 0;
      const slideInterval = 5000; // Change slides every 5 seconds
      let slideTimer;
      
      function showHomeSlide(index) {
          const slides = document.querySelectorAll('.home-slide');
          if (index >= slides.length) {
              currentHomeIndex = 0;
          } else if (index < 0) {
              currentHomeIndex = slides.length - 1;
          } else {
              currentHomeIndex = index;
          }
          slides.forEach(slide => {
              slide.style.display = 'none'; // Hide all slides
          });
          slides[currentHomeIndex].style.display = 'block'; // Show the current slide
      }
      
      function nextSlide() {
          showHomeSlide(currentHomeIndex + 1);
      }
      
      function prevSlide() {
          showHomeSlide(currentHomeIndex - 1);
      }
      
      // Start the slideshow
      function startSlideShow() {
          slideTimer = setInterval(nextSlide, slideInterval);
      }
      
      document.addEventListener('DOMContentLoaded', function() {
          showHomeSlide(currentHomeIndex); // Show the first slide
          startSlideShow(); // Start the automatic slide show
      });

      function toggleMenu() {
    const navigation = document.querySelector('.navigation');
    navigation.classList.toggle('active');
}

  </script>


<script>
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
          <li><a href="home.php" class="active">Home</a></li>
          <li><a href="about.php">About</a></li>
          <li><a href="membership.php">Memberships</a></li>
          <li><a href="classes.php">Classes</a></li>
          <li><a href="training.php">Personal Training</a></li>
          <li><a href="blog.php">Blog</a></li>
          <li><a href="contact.php">Contact Us</a></li>
          <?php if ($isLoggedIn): ?>
            <?php
                $myAccountUrl = 'customer.php'; 
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
     <!-- Image Slider Section -->
     <section id="home">
      <div class="home-slider">
          <div class="home-slide">
              <img src="images/slide1.jpg" alt="Fitness Center Image 2" />
          </div>
          <div class="home-slide">
              <img src="images/slide2.jpg" alt="Fitness Center Image 3" />
          </div>
          <div class="home-slide">
            <img src="images/slide3.jpg" alt="Fitness Center Image 3" />
        </div>
          <button class="prev" onclick="prevSlide()">&#10094;</button>
          <button class="next" onclick="nextSlide()">&#10095;</button>
      </div>
      <div class="home-overlay">
          <h1>Welcome to FitZone Fitness Center</h1>
          <p>Your journey to a healthier lifestyle begins here!</p>
          <a href="#register" class="register-btn">Join Us Today</a>
      </div>
  </section>

  <section class="why-choose-us">
    <h1>Why Choose FitZone?</h1>
    <p>Discover why FitZone is the top choice for fitness enthusiasts!</p>
    <div class="why-choose-us-container">
        <div class="reason-card">
            <i class="fas fa-heartbeat"></i>
            <h3>Dedicated to Your Wellness</h3>
            <p>We prioritize your health and well-being with personalized support to help you reach your goals.</p>
        </div>
        <div class="reason-card">
            <i class="fas fa-chalkboard-teacher"></i>
            <h3>Expert Trainers</h3>
            <p>Our certified trainers are here to guide you with tailored fitness programs and expert advice.</p>
        </div>
        <div class="reason-card">
            <i class="fas fa-medal"></i>
            <h3>High-Quality Standards</h3>
            <p>FitZone is equipped with premium facilities and equipment for a seamless workout experience.</p>
        </div>
        <div class="reason-card">
            <i class="fas fa-clock"></i>
            <h3>Flexible Hours</h3>
            <p>With extended hours and flexible membership plans, we’re here whenever you’re ready to work out.</p>
        </div>
        <div class="reason-card">
            <i class="fas fa-users"></i>
            <h3>Supportive Community</h3>
            <p>Join a community of fitness enthusiasts where you can make friends and stay motivated.</p>
        </div>
    </div>
</section>


  <section class="large-image-section">
      <img src="images/gymclass.jpg" alt="Large Image" /> 
      <div class="overlay">
          <h2>Join Our Classes</h2>
          <p>Explore our variety of fitness classes to find the right fit for you.</p>
          <a href="classes.php" class="button">Go to Classes</a>
      </div>
  </section>

<br><br><br><br>
  </main>

    <?php include 'footer.php'; ?>
    
</body>
</html>

